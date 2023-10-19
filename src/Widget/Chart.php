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
use DTL\PhpTui\Widget\Chart\Axis;
use DTL\PhpTui\Widget\Chart\ChartLayout;
use DTL\PhpTui\Widget\Chart\DataSet;

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
    public static function new(array $dataSets): self
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
        $labelY = null;

        if ($x >= $area->right() || $y < 1) {
            return null;
        }

        if ($this->xAxis->labels !== null && $y > $area->top()) {
            $xAxisY = $y;
            $y -= 1;
        }

        $yLabelX = $this->yAxis->labels !== null ? $x : null;
        $x += $this->maxWidthOfLabelsLeftOfYAxis($area, $this->yAxis->labels !== null);

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

        return new ChartLayout($graphArea, $xAxisY, $yAxisX);
    }

    private function maxWidthOfLabelsLeftOfYAxis(Area $area, bool $hasYAxis): int
    {
        $maxWidth = $this->yAxis->labels !== null ? max(
            array_map(function (Span $label) {
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
}
