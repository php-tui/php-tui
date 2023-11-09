<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

final class RectanglePainter implements ShapePainter
{
    public function draw(Painter $painter, Shape $shape): void
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
            $line->draw($painter);
        }
    }

    public static function fromScalars(float $x, float $y, int $width, int $height): self
    {
        return new self(FloatPosition::at($x, $y), $width, $height, AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}
