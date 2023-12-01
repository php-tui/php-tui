<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget\Widget;
use PhpTui\Tui\Model\Widget\WidgetRenderer;

final class RawWidgetRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof RawWidget) {
            return;
        }
        $subBuffer = Buffer::empty(Area::fromDimensions($area->width, $area->height));
        ($widget->widget)($subBuffer);
        $buffer->putBuffer($area->position, $subBuffer);
    }
}
