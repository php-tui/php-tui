<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;
use RuntimeException;

/**
 * A widget to display a progress bar.
 *
 * A `GaugeWidget` renders a bar filled according to the value given to the specified ratio. The bar width and height are defined by the area it is in.
 *
 * The associated label is always centered horizontally and vertically. If not set with
 *
 * The label is the percentage of the bar filled by default but can be overridden.
 */
final class GaugeWidget implements Widget
{
    private function __construct(
        /**
         * Ratio from 0.0 to 1.0
         */
        public float $ratio,
        /**
         * Optional label, will default to percentage (0.00%)
         */
        public ?Span $label,
        /**
         * Style of the gauge
         */
        public Style $style,
    ) {
    }

    public static function default(): self
    {
        return new self(
            ratio: 0.0,
            label: null,
            style: Style::default(),
        );
    }

    public function ratio(float $ratio): self
    {
        if ($ratio < 0 || $ratio > 1) {
            throw new RuntimeException(sprintf(
                'Gauge ratio must be between 0 and 1 got %f',
                $ratio
            ));
        }
        $this->ratio = $ratio;

        return $this;
    }

    public function label(Span $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }
}
