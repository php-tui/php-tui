<?php

namespace PhpTui\Tui\Extension\Core\Shape;

class NullShapePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
    }
}
