<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\LineSet;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Shape\Points;
use DTL\PhpTui\Widget\Canvas\Shape\Rectangle;
use DTL\PhpTui\Widget\Chart\Axis;
use DTL\PhpTui\Widget\Chart\ChartLayout;
use DTL\PhpTui\Widget\Chart\DataSet;
use RuntimeException;

final class Chart implements Widget
{
    /**
     * @param DataSet[] $dataSets
     */
    private function __construct(
        private ?Block $block,
        private Axis $xAxis,
        private Axis $yAxis,
        private array $dataSets,
        private Style $style
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        if ($area->area() === 0) {
            return;
        }
        $buffer->setStyle($area, $this->style);
        $chartArea = $this->block ? (function (Block $block, Area $area, Buffer $buffer) {
            $block->render($area, $buffer);
            return $block->inner($area);
        })($this->block, $area, $buffer) : $area;

        $layout = $this->resolveLayout($chartArea);
        if (null === $layout) {
            return;
        }

        $this->renderXLabels($buffer, $layout, $chartArea);
        $this->renderYLabels($buffer, $layout, $chartArea);

        if ($layout->xAxisY !== null) {
            for ($x = $chartArea->left(); $x < $chartArea->right(); $x++) {
                $buffer->get(Position::at($x, $layout->xAxisY))
                    ->setChar(LineSet::HORIZONTAL)
                    ->setStyle($this->xAxis->style);
            }
        }
        if ($layout->yAxisX !== null) {
            for ($y = $chartArea->top(); $y < $chartArea->bottom(); $y++) {
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
            block: null,
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
        $firstLabel = $labels[array_key_first($labels)];
        $widthBetweenTicks = intval($layout->graphArea->width / count($labels));
        $labelArea = $this->firstXLabelArea($layout->labelX, $firstLabel->width(), $widthBetweenTicks, $chartArea, $layout->graphArea);
        $labelAlignment = match ($this->xAxis->labelAlignment) {
            HorizontalAlignment::Left => HorizontalAlignment::Right,
            HorizontalAlignment::Center => HorizontalAlignment::Center,
            HorizontalAlignment::Right => HorizontalAlignment::Left,
        };

        $this->renderLabel($buffer, $firstLabel, $labelArea, $labelAlignment);

        array_shift($labels);
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

    private function firstXLabelArea(int $y, int $labelWidth, int $maxWithAfterYAxis, Area $chartArea, Area $area): Area
    {
        [$minX, $maxX] =  match ($this->xAxis->labelAlignment) {
            HorizontalAlignment::Left => [$chartArea->left(), $area->left()],
            HorizontalAlignment::Center => [$chartArea->left(), $area->left() + min($maxWithAfterYAxis, $labelWidth)],
            HorizontalAlignment::Right => [
                $chartArea->left()  > 0 ? $chartArea->left() - 1 : 0,
                $area->left() + $maxWithAfterYAxis
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

    public function block(Block $block): self
    {
        $this->block = $block;
        return $this;
    }

    public function addDataset(DataSet $dataSet): self
    {
        $this->dataSets[] = $dataSet;
        return $this;
    }
}
