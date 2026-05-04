<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Buffer;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final readonly class BufferContext
{
    public function __construct(private WidgetRenderer $renderer, public Buffer $buffer, public Area $area)
    {
    }

    public function draw(Widget $widget, ?Area $area = null): void
    {
        $this->renderer->render($this->renderer, $widget, $this->buffer, $area ?? $this->area);
    }
}
