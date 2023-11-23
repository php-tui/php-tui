<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

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
