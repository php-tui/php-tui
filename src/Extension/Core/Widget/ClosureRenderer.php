<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class ClosureRenderer implements WidgetRenderer
{
    /**
     * @param Closure(WidgetRenderer, Widget, Buffer, Area): void $renderer
     */
    public function __construct(private readonly Closure $renderer)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        ($this->renderer)($renderer, $widget, $buffer, $area);
    }
}
