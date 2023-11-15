<?php

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Extension\Core\Shape\Circle;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\FloatPosition;

class CirclePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Circle) {
            return;
        }

        foreach (range(0, 360) as $degree) {
            $radians = deg2rad($degree);
            $circleX = $shape->radius * cos($radians) + $shape->position->x;
            $circleY = $shape->radius * sin($radians) + $shape->position->y;
            if ($point = $painter->getPoint(FloatPosition::at($circleX, $circleY))) {
                $painter->paint($point, $shape->color);
            }
        }
    }
}
