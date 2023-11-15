<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\FloatPosition;

class SpritePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Sprite) {
            return;
        }

        $maxX = max(0, ...array_map(fn (string $row) => mb_strlen($row), $shape->rows));
        $xStep = $painter->context->xBounds->length() / $painter->resolution->width;
        $yStep = $painter->context->yBounds->length() / $painter->resolution->height;
        $pixelWidth = $shape->xScale;
        $pixelHeight = $shape->yScale;
        $yOffset = 0;

        $densityRatio = 1;
        foreach (array_reverse($shape->rows) as $y => $row) {
            $chars = mb_str_split($row);
            $y1 = $yOffset;
            $y2 = $yOffset + $pixelHeight;
            $yOffset += $pixelHeight;
            $xOffset = 0;

            foreach ($chars as $x => $char) {
                $x1 = $xOffset;
                $x2 = $xOffset + $pixelWidth;
                $xOffset += $pixelWidth;
                if ($char === $shape->alphaChar) {
                    continue;
                }
                for ($yF = $y1; $yF < $y2; $yF += $yStep) {
                    for ($xF = $x1; $xF < $x2; $xF += $xStep) {
                        $point = $painter->getPoint(FloatPosition::at(
                            1 + $shape->position->x + $xF,
                            $shape->position->y + $yF,
                        ));
                        if ($point === null) {
                            continue;
                        }
                        $painter->paint($point, $shape->color);
                    }
                }
            }
        }
    }
}
