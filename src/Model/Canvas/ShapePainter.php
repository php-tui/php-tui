<?php

namespace PhpTui\Tui\Model\Canvas;

interface ShapePainter
{
    public function draw(Painter $painter, Shape $shape): void;
}
