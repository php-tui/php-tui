<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick;

use PhpTui\Tui\Extension\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\Extension\ImageMagick\Widget\ImageRenderer;
use PhpTui\Tui\Model\Display\DisplayExtension;

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
        return [
            new ImageRenderer($this->imageRegistry())
        ];
    }

    private function imageRegistry(): ImageRegistry
    {
        return $this->imageRegistry ?? new ImageRegistry();
    }
}
