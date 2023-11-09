<?php

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\Canvas\Painter;

interface ShapePainter
{
    public function draw(Shape $shape): void;
}
