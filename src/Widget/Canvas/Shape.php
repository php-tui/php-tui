<?php

namespace DTL\PhpTui\Widget\Canvas;

interface Shape
{
    public function draw(Painter $painter): void;
}
