<?php

namespace PhpTui\Tui\Widget\Canvas;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;

abstract class Grid
{
    abstract public function resolution(): Resolution;

    abstract public function save(): Layer;

    abstract public function reset(): void;

    abstract public function paint(Position $position, Color $color): void;
}
