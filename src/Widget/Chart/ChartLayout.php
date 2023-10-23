<?php

namespace PhpTui\Tui\Widget\Chart;

use PhpTui\Tui\Model\Area;

class ChartLayout
{
    public function __construct(
        public Area $graphArea,
        public ?int $xAxisY,
        public ?int $yAxisX,
        public ?int $labelX,
        public ?int $labelY
    ) {
    }

}
