<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Shape\PointsShape;
use PhpTui\Tui\Extension\Core\Widget\Chart\ChartLayout;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Math\VectorUtil;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Symbol\LineSet;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use RuntimeException;

/**
 * Renders a a composite of scatter or line graphs.
 */
final class ChartRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof ChartWidget) {
            return;
        }

        if ($area->area() === 0) {
            return;
        }
        $buffer->setStyle($area, $widget->style);

        $layout = $this->resolveLayout($widget, $area);
        if (null === $layout) {
            return;
        }

        $this->renderXLabels($widget, $buffer, $layout, $area);
        $this->renderYLabels($widget, $buffer, $layout, $area);

        if ($layout->xAxisY !== null) {
            for ($x = $layout->graphArea->left(); $x < $layout->graphArea->right(); $x++) {
                $buffer->get(Position::at($x, $layout->xAxisY))
                    ->setChar(LineSet::HORIZONTAL)
                    ->setStyle($widget->xAxis->style);
            }
        }
        if ($layout->yAxisX !== null) {
            for ($y = $layout->graphArea->top(); $y < $layout->graphArea->bottom(); $y++) {
                $buffer->get(Position::at($layout->yAxisX, $y))
                    ->setChar(LineSet::VERTICAL)
                    ->setStyle($widget->yAxis->style);
            }
        }
        if ($layout->yAxisX !== null && $layout->xAxisY !== null) {
            $buffer->get(Position::at($layout->yAxisX, $layout->xAxisY))
                ->setChar(LineSet::BOTTOM_LEFT)
                ->setStyle($widget->yAxis->style);
        }

        foreach ($widget->dataSets as $dataSet) {
            $subBuffer = Buffer::empty($layout->graphArea);
            $renderer->render($renderer, CanvasWidget::default()
                ->backgroundColor($widget->style->bg ?? AnsiColor::Reset)
                ->xBounds($widget->xAxis->bounds)
                ->yBounds($widget->yAxis->bounds)
                ->marker($dataSet->marker)
                ->paint(function (CanvasContext $context) use ($dataSet): void {
                    $context->draw(PointsShape::new($dataSet->data, $dataSet->style->fg ?? AnsiColor::Reset));
                }), $subBuffer);
            $buffer->putBuffer($layout->graphArea->position, $subBuffer);

        }
    }

    private function resolveLayout(ChartWidget $chart, Area $area): ?ChartLayout
    {
        $x = $area->left();
        $y = $area->bottom() - 1;
        $xAxisY = null;
        $yAxisX = null;
        $xLabelY = null;

        if ($x >= $area->right() || $y < 1) {
            return null;
        }

        if ($chart->xAxis->labels && $y > $area->top()) {
            $xLabelY = $y;
            $y -= 1;
        }

        $yLabelX = $chart->yAxis->labels !== null ? $x : null;
        $x += $this->maxWidthOfLabelsLeftOfYAxis($chart, $area, $chart->yAxis->labels !== null);

        if ($chart->xAxis->labels !== null && $y > $area->top()) {
            $xAxisY = $y;
            $y -= 1;
        }

        if ($chart->yAxis->labels !== null && $x + 1 < $area->right()) {
            $yAxisX = $x;
            $x += 1;
        }

        $graphArea = Area::fromScalars(
            $x,
            $area->top(),
            $area->right() - $x,
            $y - $area->top() + 1
        );

        return new ChartLayout($graphArea, $xAxisY, $yAxisX, $xLabelY, $yLabelX);
    }

    private function maxWidthOfLabelsLeftOfYAxis(ChartWidget $chart, Area $area, bool $hasYAxis): int
    {
        $maxWidth = VectorUtil::max(array_map(function (Span $label): int {
            return $label->width();
        }, $chart->yAxis->labels ?? [])) ?? 0;

        if ($chart->xAxis->labels !== null && count($chart->xAxis->labels)) {
            $first = $chart->xAxis->labels[array_key_first($chart->xAxis->labels)];
            $firstLabelWidth = $first->width();
            $widthOfYAxis = match ($chart->xAxis->labelAlignment) {
                HorizontalAlignment::Left => $firstLabelWidth - (int) $hasYAxis,
                HorizontalAlignment::Center => $firstLabelWidth / 2,
                HorizontalAlignment::Right => 0,
            };
            $maxWidth = max($maxWidth, $widthOfYAxis);
        }

        return min($maxWidth, $area->width / 3);
    }

    private function renderXLabels(ChartWidget $chart, Buffer $buffer, ChartLayout $layout, Area $chartArea): void
    {
        if (null === $layout->labelX) {
            return;
        }
        $labels = $chart->xAxis->labels ?? [];
        if (count($labels) < 1) {
            return;
        }
        $widthBetweenTicks = (int) ($layout->graphArea->width / count($labels));
        $labelAlignment = match ($chart->xAxis->labelAlignment) {
            HorizontalAlignment::Left => HorizontalAlignment::Right,
            HorizontalAlignment::Center => HorizontalAlignment::Center,
            HorizontalAlignment::Right => HorizontalAlignment::Left,
        };

        $firstLabel = $labels[array_key_first($labels)];
        array_shift($labels);
        $labelArea = $this->firstXLabelArea(
            $chart,
            $layout->labelX,
            $firstLabel->width(),
            $widthBetweenTicks,
            $chartArea,
            $layout->graphArea
        );
        $this->renderLabel($buffer, $firstLabel, $labelArea, $labelAlignment);

        $lastLabel = array_pop($labels);
        foreach ($labels as $i => $label) {
            $x = $layout->graphArea->left() + ($i + 1) * $widthBetweenTicks + 1;
            $labelArea = Area::fromScalars($x, $layout->labelX, $widthBetweenTicks - 1, 1);
            $this->renderLabel($buffer, $label, $labelArea, HorizontalAlignment::Center);
        }
        $x = $layout->graphArea->right() - $widthBetweenTicks;
        $labelArea = Area::fromScalars($x, $layout->labelX, $widthBetweenTicks, 1);
        if ($lastLabel) {
            $this->renderLabel($buffer, $lastLabel, $labelArea, HorizontalAlignment::Center);
        }

    }

    private function firstXLabelArea(ChartWidget $chart, int $y, int $labelWidth, int $maxWithAfterYAxis, Area $chartArea, Area $graphArea): Area
    {
        [$minX, $maxX] =  match ($chart->xAxis->labelAlignment) {
            HorizontalAlignment::Left => [$chartArea->left(), $graphArea->left()],
            HorizontalAlignment::Center => [$chartArea->left(), $graphArea->left() + min($maxWithAfterYAxis, $labelWidth)],
            HorizontalAlignment::Right => [
                max($graphArea->left() - 1, 0),
                $graphArea->left() + $maxWithAfterYAxis
            ],
        };

        return Area::fromScalars($minX, $y, $maxX - $minX, 1);
    }

    private function renderLabel(Buffer $buffer, Span $label, Area $labelArea, HorizontalAlignment $labelAlignment): void
    {
        $boundedLabelWidth = min($labelArea->width, $label->width());
        $x = match ($labelAlignment) {
            HorizontalAlignment::Left => $labelArea->left(),
            HorizontalAlignment::Center => $labelArea->left() + $labelArea->width / 2 - $boundedLabelWidth / 2,
            HorizontalAlignment::Right => $labelArea->right() - $boundedLabelWidth,
        };

        $buffer->putSpan(Position::at((int) $x, $labelArea->top()), $label, $boundedLabelWidth);
    }

    private function renderYLabels(ChartWidget $chart, Buffer $buffer, ChartLayout $layout, Area $chartArea): void
    {
        if ($layout->labelY === null) {
            return;
        }
        $labels = $chart->yAxis->labels;
        if (null === $labels) {
            return;
        }
        $labelsLen = count($labels);
        foreach ($labels as $i => $label) {
            $dy = $labelsLen > 1 ? (int) ($i * ($layout->graphArea->height - 1) / ($labelsLen - 1)) : 0;
            if ($dy < $layout->graphArea->bottom()) {
                $labelArea = Area::fromScalars(
                    $layout->labelY,
                    max(0, $layout->graphArea->bottom() - 1 - $dy),
                    max(0, ($layout->graphArea->left() - $chartArea->left()) - 1),
                    1
                );
                $this->renderLabel($buffer, $label, $labelArea, $chart->yAxis->labelAlignment);
            }
        }
    }
}
