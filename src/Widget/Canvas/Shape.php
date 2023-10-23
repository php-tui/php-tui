<?php

namespace PhpTui\Tui\Widget\Canvas;

interface Shape
{
    public function draw(Painter $painter): void;
}
