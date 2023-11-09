<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;

class ClosurePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof ClosureShape) {
            return;
        }
        ($shape->closure)($painter);
    }
}
