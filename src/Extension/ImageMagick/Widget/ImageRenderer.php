<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\ImageMagick\ImageRegistry;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class ImageRenderer implements WidgetRenderer
{
    public function __construct(private readonly ImageRegistry $registry)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof ImageWidget) {
            return;
        }

        $image = $this->registry->load($widget->path);
        $geo = $image->getImageGeometry();

        $renderer->render($renderer, CanvasWidget::fromIntBounds(
            0,
            $geo['width'] - 1,
            0,
            $geo['height'],
        )->marker($widget->marker ?? Marker::HalfBlock)->draw(ImageShape::fromPath(
            $widget->path
        )), $buffer);
    }
}
