<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

class LabelInfo
{
    public function __construct(
        public readonly bool $groupLabelVisible,
        public readonly bool $barLabelVisible,
        /**
         * @var int<0,max>
         */
        public readonly int $height
    ) {
    }

}
