<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

interface ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void;
}
