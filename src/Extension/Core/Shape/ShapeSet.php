<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

interface ShapeSet
{
    /**
     * @return ShapePainter[]
     */
    public function shapes(): array;
}
