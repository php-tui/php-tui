<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use Imagick;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\ImageMagick\ImageRegistry;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget\Widget;
use PhpTui\Tui\Model\Widget\WidgetRenderer;

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

        if (class_exists(Imagick::class)) {
            $image = $this->registry->load($widget->path);
            $geo = $image->getImageGeometry();
        } else {
            // otherwise extension not loaded, image shape will show a
            // placeholder!
            $geo = [ 'width' => 100, 'height' => 100 ];
        }

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
