<?php

namespace PhpTui\Tui\Extension\Core\Widget\Buffer;

use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class BufferContext
{
    public function __construct(private WidgetRenderer $renderer, public readonly Buffer $buffer)
    {
    }

    public function draw(Widget $widget): void
    {
        $this->renderer->render($this->renderer, $widget, $this->buffer);
    }
}
