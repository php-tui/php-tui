<?php

namespace PhpTui\Tui\Adapter\ImageMagick;

use PhpTui\Tui\Adapter\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\Model\DisplayExtension;

final class ImageMagickExtension implements DisplayExtension
{
    public function __construct(private ?ImageRegistry $imageRegistry = null)
    {
    }

    public function shapePainters(): array
    {
        return [
            new ImagePainter($this->imageRegistry())
        ];
    }

    public function widgetRenderers(): array
    {
        return [];
    }

    private function imageRegistry(): ImageRegistry
    {
        return $this->imageRegistry ?? new ImageRegistry();
    }
}
