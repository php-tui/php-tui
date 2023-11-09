<?php

namespace PhpTui\Tui\Adapter\ImageMagick;

use PhpTui\Tui\Adapter\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\Model\Canvas\ShapeSet;

class ImageMagickShapeSet implements ShapeSet
{
    public function shapes(): array
    {
        return [
            new ImagePainter(),
        ];
    }
}
