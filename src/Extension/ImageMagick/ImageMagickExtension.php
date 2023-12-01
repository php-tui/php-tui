<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick;

use PhpTui\Tui\Display\DisplayExtension;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\Extension\ImageMagick\Widget\ImageRenderer;

final class ImageMagickExtension implements DisplayExtension
{
    public function __construct(private readonly ?ImageRegistry $imageRegistry = null)
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
