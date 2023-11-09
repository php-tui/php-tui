<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

class PointsPainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Points) {
            return;
        }

        foreach ($shape->coords as [$x, $y]) {
            if (!$point = $painter->getPoint(FloatPosition::at($x, $y))) {
                continue;
            }
            $painter->paint($point, $shape->color);
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
