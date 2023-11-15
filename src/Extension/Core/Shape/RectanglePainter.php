<?php

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Extension\Core\Shape\Line;
use PhpTui\Tui\Extension\Core\Shape\Rectangle;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

final class RectanglePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Rectangle) {
            return;
        }

        $lines = [
            Line::fromScalars(
                $shape->position->x,
                $shape->position->y,
                $shape->position->x,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            Line::fromScalars(
                $shape->position->x,
                $shape->position->y + $shape->height,
                $shape->position->x + $shape->width,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            Line::fromScalars(
                $shape->position->x + $shape->width,
                $shape->position->y,
                $shape->position->x + $shape->width,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            Line::fromScalars(
                $shape->position->x,
                $shape->position->y,
                $shape->position->x + $shape->width,
                $shape->position->y,
            )->color($shape->color),
        ];

        foreach ($lines as $line) {
            $shapePainter->draw($shapePainter, $painter, $line);
        }
    }
}
