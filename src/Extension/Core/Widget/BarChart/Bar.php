<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Line;

final class Bar
{
    public function __construct(
        /**
         * Value to display on the bar
         */
        public int $value,
        /**
         * Optional label to be printed under the bar
         */
        public ?Line $label,
        /**
         * Style for the bar
         */
        public Style $style,
        /**
         * Style of the valyue printed at the bottom of the bar
         */
        public Style $valueStyle,
        /**
         * Optional text value to be shown on the bar insteadf of the actual value
         */
        public ?string $textValue,
    ) {
    }
}
