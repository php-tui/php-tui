<?php

namespace PhpTui\Tui\Adapter\ImageMagick;

use PhpTui\Tui\Adapter\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\DisplayExtension;

final class ImageMagickExtension implements DisplayExtension
{
    public function build(DisplayBuilder $builder): void
    {
        $builder->addShapePainter(new ImagePainter(
            $this->imageRegistry(),
        ));
    }

    private function imageRegistry(): ImageRegistry
    {
        return new ImageRegistry();
    }
}
