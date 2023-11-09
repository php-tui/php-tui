<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

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
    ) {
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
