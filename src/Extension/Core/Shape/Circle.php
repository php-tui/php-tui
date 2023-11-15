<?php

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Draws a circle at with the specified radius and color
 */
final class Circle implements Shape
{
    public function __construct(
        /**
         * Position of the circle
         */
        public FloatPosition $position,
        /**
         * Radius of the circle
         */
        public float $radius,
        /**
         * Color of the circle
         */
        public Color $color,
    ) {
    }

    public static function fromScalars(float $x, float $y, float $radius): self
    {
        return new self(FloatPosition::at($x, $y), $radius, AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}
