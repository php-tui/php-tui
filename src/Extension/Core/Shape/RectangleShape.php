<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FloatPosition;

/**
 * Draw a rectangle at the given position with the given width and height
 */
final class RectangleShape implements Shape
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
