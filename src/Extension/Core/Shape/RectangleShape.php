<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;

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
