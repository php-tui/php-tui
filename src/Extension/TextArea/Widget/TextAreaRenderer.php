<?php

namespace PhpTui\Tui\Extension\TextArea\Widget;

use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class TextAreaRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        if (!$widget instanceof TextAreaWidget) {
            return;
        }
    }
}
