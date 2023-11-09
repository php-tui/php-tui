<?php

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\Canvas\Painter;

interface Shape
{
    public function draw(Painter $painter): void;
}
