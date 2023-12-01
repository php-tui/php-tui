<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\Position;

abstract class CanvasGrid
{
    abstract public function resolution(): Resolution;

    abstract public function save(): Layer;

    abstract public function reset(): void;

    abstract public function paint(Position $position, Color $color): void;
}
