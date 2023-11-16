<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

class NullShapePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
    }
}
