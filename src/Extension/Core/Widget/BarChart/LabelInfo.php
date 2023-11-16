<?php

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

class LabelInfo
{
    public function __construct(
        public readonly bool $groupLabelVisible,
        public readonly bool $barLabelVisible,
        public readonly int $height
    )
    {
    }

}
