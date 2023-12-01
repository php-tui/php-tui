<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Chart;

use PhpTui\Tui\Model\Display\Area;

final class ChartLayout
{
    public function __construct(
        public Area $graphArea,
        /**
         * @var int<0,max>
         */
        public ?int $xAxisY,
        /**
         * @var int<0,max>
         */
        public ?int $yAxisX,
        /**
         * @var int<0,max>
         */
        public ?int $labelX,
        /**
         * @var int<0,max>
         */
        public ?int $labelY
    ) {
    }

}
