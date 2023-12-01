<?php

declare(strict_types=1);

namespace PhpTui\Tui\Widget\WidgetRenderer;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

/**
 * This renderer does nothing.
 *
 * It should typically be used as the "renderer" when
 * calling the aggregate renderer to satisfy the contract.
 */
final class NullWidgetRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
    }
}
