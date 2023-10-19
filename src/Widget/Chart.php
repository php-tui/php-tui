<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
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
    ) {}

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
        foreach ($this->dataSets as $dataSet) {
            Canvas::default()
                ->backgroundColor($this->style->bg ?: AnsiColor::Reset)
                ->xBounds($this->xAxis->bounds)
                ->yBounds($this->yAxis->bounds)
                ->marker($dataSet->marker)
                ->paint(function (CanvasContext $context) {
                    $context->draw(new Points($dataSet->data, $dataSet->style->fg ?: Color::Reset));
                });

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

    private function resolveLayout(Area $area): ?ChartLayout
    {
        $x = $area->left();
        $y = $area->bottom() - 1;

        if ($x >= $area->right() || $y < 1) {
            return null;
        }

        $graphArea = Area::fromPrimitives(
            $x,
            $area->top(),
            $area->right() - $x,
            $area->top() + 1
        );

        return new ChartLayout($graphArea);
    }
}
