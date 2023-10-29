<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\LineSet;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Points;
use PhpTui\Tui\Widget\Chart\Axis;
use PhpTui\Tui\Widget\Chart\ChartLayout;
use PhpTui\Tui\Widget\Chart\DataSet;
use RuntimeException;

final class Chart implements Widget
{
    /**
     * @param DataSet[] $dataSets
     */
    private function __construct(
        /**
         * The X-Axis: bounds, style, labels etc.
         */
        private Axis $xAxis,
        /**
         * The Y-Axis: bounds, style, labels etc.
         */
        private Axis $yAxis,
        /**
         * The data sets.
         */
        private array $dataSets,
        /**
         * Style for the chart's area
         */
        private Style $style
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        if ($area->area() === 0) {
            return;
        }
        $buffer->setStyle($area, $this->style);

        $layout = $this->resolveLayout($area);
        if (null === $layout) {
            return;
        }

        $this->renderXLabels($buffer, $layout, $area);
        $this->renderYLabels($buffer, $layout, $area);

        if ($layout->xAxisY !== null) {
            for ($x = $layout->graphArea->left(); $x < $layout->graphArea->right(); $x++) {
                $buffer->get(Position::at($x, $layout->xAxisY))
                    ->setChar(LineSet::HORIZONTAL)
                    ->setStyle($this->xAxis->style);
            }
        }
        if ($layout->yAxisX !== null) {
            for ($y = $layout->graphArea->top(); $y < $layout->graphArea->bottom(); $y++) {
                $buffer->get(Position::at($layout->yAxisX, $y))
                    ->setChar(LineSet::VERTICAL)
                    ->setStyle($this->yAxis->style);
            }
        }
        if ($layout->yAxisX !== null && $layout->xAxisY !== null) {
            $buffer->get(Position::at($layout->yAxisX, $layout->xAxisY))
                ->setChar(LineSet::BOTTOM_LEFT)
                ->setStyle($this->yAxis->style);
        }


        foreach ($this->dataSets as $dataSet) {
            Canvas::default()
                ->backgroundColor($this->style->bg ?: AnsiColor::Reset)
                ->xBounds($this->xAxis->bounds)
                ->yBounds($this->yAxis->bounds)
                ->marker($dataSet->marker)
                ->paint(function (CanvasContext $context) use ($dataSet): void {
                    $context->draw(Points::new($dataSet->data, $dataSet->style->fg ?: AnsiColor::Reset));
                })
                ->render($layout->graphArea, $buffer);

        }

    }

    /**
     * @param DataSet[] $dataSets
     */
    public static function new(array $dataSets = []): self
    {
        return new self(
            xAxis: Axis::default(),
            yAxis: Axis::default(),
            dataSets: $dataSets,
            style: Style::default()
        );
    }

    public function xAxis(Axis $axis): self
    {
        $this->xAxis = $axis;
        return $this;
    }
    public function yAxis(Axis $axis): self
    {
        $this->yAxis = $axis;
        return $this;
    }

    public function addDataset(DataSet $dataSet): self
    {
        $this->dataSets[] = $dataSet;
        return $this;
    }

    private function resolveLayout(Area $area): ?ChartLayout
    {
        $x = $area->left();
        $y = $area->bottom() - 1;
        $xAxisY = null;
        $yAxisX = null;
        $xLabelY = null;

        if ($x >= $area->right() || $y < 1) {
            return null;
        }

        if ($this->xAxis->labels && $y > $area->top()) {
            $xLabelY = $y;
            $y -= 1;
        }

        $yLabelX = $this->yAxis->labels !== null ? $x : null;
        $x += $this->maxWidthOfLabelsLeftOfYAxis($area, $this->yAxis->labels !== null);

        if ($this->xAxis->labels !== null && $y > $area->top()) {
            $xAxisY = $y;
            $y -= 1;
        }

        if ($this->yAxis->labels !== null && $x + 1 < $area->right()) {
            $yAxisX = $x;
            $x += 1;
        }

        $graphArea = Area::fromPrimitives(
            $x,
            $area->top(),
            $area->right() - $x,
            $y - $area->top() + 1
        );

        return new ChartLayout($graphArea, $xAxisY, $yAxisX, $xLabelY, $yLabelX);
    }

    private function maxWidthOfLabelsLeftOfYAxis(Area $area, bool $hasYAxis): int
    {
        $maxWidth = $this->yAxis->labels ? max(
            ...array_map(function (Span $label) {
                return $label->width();
            }, $this->yAxis->labels)
        ) : 0;

        if ($this->xAxis->labels !== null && count($this->xAxis->labels)) {
            $first = $this->xAxis->labels[array_key_first($this->xAxis->labels)];
            $firstLabelWidth = $first->width();
            $widthOfYAxis = match ($this->xAxis->labelAlignment) {
                HorizontalAlignment::Left => $firstLabelWidth - $hasYAxis ? 1 : 0,
                HorizontalAlignment::Center => $firstLabelWidth / 2,
                HorizontalAlignment::Right => 0,
            };
            $maxWidth = max($maxWidth, $widthOfYAxis);
        }

        return min($maxWidth, $area->width / 3);
    }

    private function renderXLabels(Buffer $buffer, ChartLayout $layout, Area $chartArea): void
    {
        if (null === $layout->labelX) {
            return;
        }
        $labels = $this->xAxis->labels ?: [];
        if (count($labels) < 2) {
            return;
        }
        $widthBetweenTicks = intval($layout->graphArea->width / count($labels));
        $labelAlignment = match ($this->xAxis->labelAlignment) {
            HorizontalAlignment::Left => HorizontalAlignment::Right,
            HorizontalAlignment::Center => HorizontalAlignment::Center,
            HorizontalAlignment::Right => HorizontalAlignment::Left,
        };

        $firstLabel = $labels[array_key_first($labels)];
        array_shift($labels);
        $labelArea = $this->firstXLabelArea(
            $layout->labelX,
            $firstLabel->width(),
            $widthBetweenTicks,
            $chartArea,
            $layout->graphArea
        );
        $this->renderLabel($buffer, $firstLabel, $labelArea, $labelAlignment);

        $lastLabel = array_pop($labels);
        if (null === $lastLabel) {
            throw new RuntimeException('Last label is null, this should not happen');
        }
        foreach ($labels as $i => $label) {
            $x = $layout->graphArea->left() + ($i + 1) * $widthBetweenTicks + 1;
            $labelArea = Area::fromPrimitives($x, $layout->labelX, $widthBetweenTicks -1, 1);
            $this->renderLabel($buffer, $label, $labelArea, HorizontalAlignment::Center);
        }
        $x = $layout->graphArea->right() - $widthBetweenTicks;
        $labelArea = Area::fromPrimitives($x, $layout->labelX, $widthBetweenTicks, 1);
        $this->renderLabel($buffer, $lastLabel, $labelArea, HorizontalAlignment::Center);

    }

    private function firstXLabelArea(int $y, int $labelWidth, int $maxWithAfterYAxis, Area $chartArea, Area $graphArea): Area
    {
        [$minX, $maxX] =  match ($this->xAxis->labelAlignment) {
            HorizontalAlignment::Left => [$chartArea->left(), $graphArea->left()],
            HorizontalAlignment::Center => [$chartArea->left(), $graphArea->left() + min($maxWithAfterYAxis, $labelWidth)],
            HorizontalAlignment::Right => [
                max($graphArea->left() - 1, 0),
                $graphArea->left() + $maxWithAfterYAxis
            ],
        };

        return Area::fromPrimitives($minX, $y, $maxX - $minX, 1);
    }

    private function renderLabel(Buffer $buffer, Span $label, Area $labelArea, HorizontalAlignment $labelAlignment): void
    {
        $boundedLabelWidth = min($labelArea->width, $label->width());
        $x = match ($labelAlignment) {
            HorizontalAlignment::Left => $labelArea->left(),
            HorizontalAlignment::Center => $labelArea->left() + $labelArea->width / 2 - $boundedLabelWidth / 2,
            HorizontalAlignment::Right => $labelArea->right() - $boundedLabelWidth,
        };

        $buffer->putSpan(Position::at(intval($x), $labelArea->top()), $label, $boundedLabelWidth);
    }

    private function renderYLabels(Buffer $buffer, ChartLayout $layout, Area $chartArea): void
    {
        if ($layout->labelY === null) {
            return;
        }
        $labels = $this->yAxis->labels;
        if (null === $labels) {
            return;
        }
        $labelsLen = count($labels);
        foreach ($labels as $i => $label) {
            $dy = intval($i * ($layout->graphArea->height - 1) / ($labelsLen - 1));
            if ($dy < $layout->graphArea->bottom()) {
                $labelArea = Area::fromPrimitives(
                    $layout->labelY,
                    max(0, $layout->graphArea->bottom() - 1 - $dy),
                    max(0, ($layout->graphArea->left() - $chartArea->left()) - 1),
                    1
                );
                $this->renderLabel($buffer, $label, $labelArea, $this->yAxis->labelAlignment);
            }
        }
    }
}
