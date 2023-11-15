<?php

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;

class NullShapePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
    }
}
