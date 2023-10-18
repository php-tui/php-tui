<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Position;

abstract class Grid
{
    abstract public function resolution(): Resolution;

    abstract public function save(): Layer;

    abstract public function reset(): void;

    abstract public function paint(Position $position, Color $color);
}
