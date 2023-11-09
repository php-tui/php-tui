<?php

namespace PhpTui\Tui\Model\Canvas;

interface ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void;
}
