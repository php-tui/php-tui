<?php

namespace PhpTui\Tui\Widget\Canvas\Shape;

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
        private array $rows,
        private Color $color,
        public  FloatPosition $position,
        private string $alphaChar = ' ',
        private float $xScale = 1.0,
        private int $density = 1,
        private float $yScale = 1.0,
    ) {
    }

    public function draw(Painter $painter): void
    {
        $maxX = max(...array_map(fn (string $row) => mb_strlen($row), $this->rows));
        foreach (array_reverse($this->rows) as $y => $row) {
            $chars = mb_str_split($row);
            for ($x = 1; $x < $maxX; $x+=1/$this->density) {
                $intX = intval(floor($x));
                if (!isset($chars[$intX])) {
                    $chars[$intX] = $this->alphaChar;
                }
                if ($chars[$intX] === $this->alphaChar) {
                    continue;
                }
                $point = $painter->getPoint(FloatPosition::at(
                    $this->position->x + $x * $this->xScale,
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
