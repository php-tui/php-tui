<?php

namespace PhpTui\Tui\Widget\Canvas\Shape;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Draw a rectangle at the given position with the given width and height
 */
final class Rectangle implements Shape
{
    public function __construct(
        /**
         * Position to draw the rectangle (bottom left corner)
         */
        private FloatPosition $position,
        /**
         * Width of the rectangle
         */
        private int $width,
        /**
         * Height of the rectangle
         */
        private int $height,
        /**
         * Color of the rectangle
         */
        private Color $color,
    ) {
    }

    public function draw(Painter $painter): void
    {
        $lines = [
            Line::fromPrimitives(
                $this->position->x,
                $this->position->y,
                $this->position->x,
                $this->position->y + $this->height,
                $this->color
            ),
            Line::fromPrimitives(
                $this->position->x,
                $this->position->y + $this->height,
                $this->position->x + $this->width,
                $this->position->y + $this->height,
                $this->color
            ),
            Line::fromPrimitives(
                $this->position->x + $this->width,
                $this->position->y,
                $this->position->x + $this->width,
                $this->position->y + $this->height,
                $this->color
            ),
            Line::fromPrimitives(
                $this->position->x,
                $this->position->y,
                $this->position->x + $this->width,
                $this->position->y,
                $this->color
            ),
        ];

        foreach ($lines as $line) {
            $line->draw($painter);
        }
    }

    public static function fromPrimitives(float $x, float $y, int $width, int $height, Color $color): self
    {
        return new self(FloatPosition::at($x, $y), $width, $height, $color);
    }
}
