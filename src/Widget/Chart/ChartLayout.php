<?php

namespace DTL\PhpTui\Widget\Chart;

use DTL\PhpTui\Model\Area;

class ChartLayout
{
    public function __construct(
        public Area $graphArea,
        public ?int $xAxisY,
        public ?int $yAxisX,
        public ?int $labelX,
        public ?int $labelY
    )
    {
    }

}
