<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Draw a rectangle at the given position with the given width and height
 */
final class Rectangle implements Shape
{
    public function __construct(
        /**
         * Position to draw the rectangle (bottom left corner)
         */
        public FloatPosition $position,
        /**
         * Width of the rectangle
         */
        public int $width,
        /**
         * Height of the rectangle
         */
        public int $height,
        /**
         * Color of the rectangle
         */
        public Color $color,
    ) {
    }

    public static function fromScalars(float $x, float $y, int $width, int $height): self
    {
        return new self(FloatPosition::at($x, $y), $width, $height, AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}
