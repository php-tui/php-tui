<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class BufferWidgetRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        if (!$widget instanceof BufferWidget) {
            return;
        }
        $subBuffer = Buffer::empty(Area::fromScalars(
            $buffer->area()->position->x,
            $buffer->area()->position->y,
            $area->width,
            $area->height
        ));
        $context = new BufferContext($renderer, $subBuffer);
        ($widget->widget)($context);
        $buffer->putBuffer($area->position, $subBuffer);
    }
}
