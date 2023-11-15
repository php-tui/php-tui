<?php

namespace PhpTui\Tui\Model\Canvas;

interface ShapeSet
{
    /**
     * @return ShapePainter[]
     */
    public function shapes(): array;
}
