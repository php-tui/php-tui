<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

use PhpTui\Tui\Canvas\Painter;
use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Canvas\ShapePainter;

interface ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void;
}
