<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Canvas\ShapeSet;

class DefaultShapeSet implements ShapeSet
{
    public function shapes(): array
    {
        return [
            new CirclePainter(),
            new LinePainter(),
            new MapPainter(),
            new PointsPainter(),
            new RectanglePainter(),
            new SpritePainter(),
        ];
    }
}
