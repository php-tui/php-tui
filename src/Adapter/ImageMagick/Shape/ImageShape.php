<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use Imagick;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

final class ImageShape implements Shape
{
    public function __construct(
        public readonly Imagick $image
    ) {}

    public function draw(Painter $painter): void
    {
    }
}
