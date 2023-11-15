<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class RawWidgetRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof RawWidget) {
            return;
        }
        $subBuffer = Buffer::empty(Area::fromDimensions($area->width, $area->height));
        ($widget->widget)($subBuffer);
        $buffer->putBuffer($area->position, $subBuffer);
    }
}
