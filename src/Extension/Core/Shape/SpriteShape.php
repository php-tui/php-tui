<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FloatPosition;

/**
 * Renders a "sprite" based on a given "ascii art"
 *
 * Each sprite can have a single color but they can be layered on the canvas.
 */
final class SpriteShape implements Shape
{
    /**
     * @param array<int,string> $rows
     */
    public function __construct(
        /**
         * Set of lines/rows which make up the Sprite. e.g. `['    ', '  x  ']`. The lines do not have to be of equal length.
         */
        public array $rows,
        /**
         * Color of the sprite
         */
        public Color $color,
        /**
         * Position to place the sprite at (bottom left)
         */
        public FloatPosition $position,
        /**
         * Character to use as the "alpha" (transparent) "channel".
         * Defaults to empty space.
         */
        public string $alphaChar = ' ',

        /**
         * X scale
         */
        public float $xScale = 1.0,
        /**
         * Y scale
         */
        public float $yScale = 1.0,
    ) {
    }
}
