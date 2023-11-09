<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Renders a "sprite" based on a given "ascii art"
 * Each sprite can have a single color but they can be layered on the canvas.
 */
class Sprite implements Shape
{
    /**
     * @param array<int,string> $rows
     */
    public function __construct(
        /**
         * Set of lines/rows which make up the Sprite. e.g. `['    ', '  x  ']`. The lines do not have to be of equal length.
         */
        private array $rows,
        /**
         * Color of the sprite
         */
        public Color $color,
        /**
         * Position to place the sprite at (bottom left)
         */
        public  FloatPosition $position,
        /**
         * Character to use as the "alpha" (transparent) "channel".
         * Defaults to empty space.
         */
        private string $alphaChar = ' ',
        /**
         * X scale
         */
        public float $xScale = 1.0,
        /**
         * Density
         */
        private int $density = 1,
        /**
         * Y scale
         */
        public float $yScale = 1.0,
    ) {
    }

    public function draw(Painter $painter): void
    {
        $maxX = max(0, ...array_map(fn (string $row) => mb_strlen($row), $this->rows));
        $densityRatio = 1/$this->density;
        foreach (array_reverse($this->rows) as $cellY => $row) {
            // fill the cell (step 1) from top to bottom.
            // if density = 4 and cell is 0,0 start at 0,75 then 0,5, 0.25, 0
            for ($y = $cellY + 1 - $densityRatio; $y >= $cellY; $y -= $densityRatio) {
                $chars = mb_str_split($row);

                // fill the cell from left to right
                // if desity = 4 and 0,0 then 0,0, 0.25, 0.5, 0.75
                for ($x = 0; $x < $maxX; $x += $densityRatio) {
                    $cellX = intval(floor($x));
                    if (!isset($chars[$cellX])) {
                        $chars[$cellX] = $this->alphaChar;
                    }
                    if ($chars[$cellX] === $this->alphaChar) {
                        continue;
                    }
                    $point = $painter->getPoint(FloatPosition::at(
                        1 + $this->position->x + $x * $this->xScale,
                        $this->position->y + $y * $this->yScale,
                    ));
                    if (null === $point) {
                        continue;
                    }
                    $painter->paint($point, $this->color);
                }
            }

        }
    }
}
