<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class CompositeRenderer implements WidgetRenderer
{
    public function render(
        WidgetRenderer $renderer,
        Widget $widget,
        Buffer $buffer
    ): void {
        if (!$widget instanceof CompositeWidget) {
            return;
        }

        array_map(function (Widget $widget) use ($renderer, $buffer): void {
            $renderer->render($renderer, $widget, $buffer);
        }, $widget->widgets);
    }
}
