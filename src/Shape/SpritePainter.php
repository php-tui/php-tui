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
        $pixelWidth = $shape->xScale;
        $pixelHeight = $shape->yScale;
        
        $densityRatio = 1;
        foreach (array_reverse($shape->rows) as $y => $row) {
            $chars = mb_str_split($row);

            // fill the cell from left to right
            // if desity = 4 and 0,0 then 0,0, 0.25, 0.5, 0.75
            foreach ($chars as $x => $char) {
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
