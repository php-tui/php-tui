<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Canvas\Painter;
use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Canvas\ShapePainter;

final class RectanglePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof RectangleShape) {
            return;
        }

        $lines = [
            LineShape::fromScalars(
                $shape->position->x,
                $shape->position->y,
                $shape->position->x,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            LineShape::fromScalars(
                $shape->position->x,
                $shape->position->y + $shape->height,
                $shape->position->x + $shape->width,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            LineShape::fromScalars(
                $shape->position->x + $shape->width,
                $shape->position->y,
                $shape->position->x + $shape->width,
                $shape->position->y + $shape->height,
            )->color($shape->color),
            LineShape::fromScalars(
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
