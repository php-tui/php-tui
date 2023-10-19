<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\Chart\Axis;

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
    }

    /**
     * @param DataSet[] $dataSet
     * @param array<int,mixed> $dataSets
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
}
