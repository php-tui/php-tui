<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;

class SpritePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Sprite) {
            return;
        }

        $maxX = max(0, ...array_map(fn (string $row) => mb_strlen($row), $shape->rows));
        $densityRatio = 1/$shape->density;
        foreach (array_reverse($shape->rows) as $cellY => $row) {
            // fill the cell (step 1) from top to bottom.
            // if density = 4 and cell is 0,0 start at 0,75 then 0,5, 0.25, 0
            for ($y = $cellY + 1 - $densityRatio; $y >= $cellY; $y -= $densityRatio) {
                $chars = mb_str_split($row);

                // fill the cell from left to right
                // if desity = 4 and 0,0 then 0,0, 0.25, 0.5, 0.75
                for ($x = 0; $x < $maxX; $x += $densityRatio) {
                    $cellX = intval(floor($x));
                    if (!isset($chars[$cellX])) {
                        $chars[$cellX] = $shape->alphaChar;
                    }
                    if ($chars[$cellX] === $shape->alphaChar) {
                        continue;
                    }
                    $point = $painter->getPoint(FloatPosition::at(
                        1 + $shape->position->x + $x * $shape->xScale,
                        $shape->position->y + $y * $shape->yScale,
                    ));
                    if (null === $point) {
                        continue;
                    }
                    $painter->paint($point, $shape->color);
                }
            }

        }
    }
}
