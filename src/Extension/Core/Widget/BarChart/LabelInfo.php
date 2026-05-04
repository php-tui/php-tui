<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

final readonly class LabelInfo
{
    public function __construct(
        public bool $groupLabelVisible,
        public bool $barLabelVisible,
        /**
         * @var int<0,max>
         */
        public int $height
    ) {
    }

}
