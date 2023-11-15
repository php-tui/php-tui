<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class ClosureRenderer implements WidgetRenderer
{
    /**
     * @param Closure(WidgetRenderer, Widget, Area, Buffer): void $renderer
     */
    public function __construct(private Closure $renderer)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        ($this->renderer)($renderer, $widget, $area, $buffer);
    }
}
