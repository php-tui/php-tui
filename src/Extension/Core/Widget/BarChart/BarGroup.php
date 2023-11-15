<?php

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Model\Widget\Line;

class BarGroup
{
    public function __construct(
        /**
         * Label of the group. It will be printed centered under
         * this group of bars
         */
        public ?Line $label,
        /**
         * List of bars to be shown
         * @var Bar[]
         */
        public array $bars,
    ) {}
}
