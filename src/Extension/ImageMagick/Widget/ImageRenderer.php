<?php

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Extension\ImageMagick\ImageRegistry;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

class ImageRenderer implements WidgetRenderer
{
    public function __construct(private ImageRegistry $registry)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof ImageWidget) {
            return;
        }

        $image = $this->registry->load($widget->path);
        $geo = $image->getImageGeometry();

        $renderer->render($renderer, Canvas::fromIntBounds(
            0,
            $geo['width'],
            0,
            $geo['height'],
        )->marker($widget->marker ?? Marker::HalfBlock)->draw(ImageShape::fromPath(
            $widget->path
        )), $area, $buffer);
    }
}
