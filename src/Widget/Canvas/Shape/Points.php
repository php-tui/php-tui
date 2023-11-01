<?php

namespace PhpTui\Tui\Widget\Canvas\Shape;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Render a set of points on the canvas.
 */
class Points implements Shape
{
    /**
     * @param array<int,array{float,float}> $coords
     */
    public function __construct(
        /**
         * Set of coordinates to draw, e.g. `[[0.0, 0.0], [2.0, 2.0], [4.0,4.0]]`
         */
        public array $coords,
        /**
         * Color of the points
         */
        public Color $color
    )
    {
    }

    public function draw(Painter $painter): void
    {
        foreach ($this->coords as [$x, $y]) {
            if (!$point = $painter->getPoint(FloatPosition::at($x, $y))) {
                continue;
            }
            $painter->paint($point, $this->color);
        }
    }

    /**
     * @param list<array{float,float}> $coords
     */
    public static function new(array $coords, Color $color): self
    {
        return new self(
            $coords,
            $color
        );
    }
}
