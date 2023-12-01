<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Line;

final class Bar
{
    public function __construct(
        /**
         * Value to display on the bar
         * @var int<0,max>
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

    /**
     * @param int<0,max> $value
     */
    public static function fromValue(int $value): self
    {
        return new self($value, null, Style::default(), Style::default(), null);
    }

    public function label(Line $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function textValue(string $textValue): self
    {
        $this->textValue = $textValue;

        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }
}
