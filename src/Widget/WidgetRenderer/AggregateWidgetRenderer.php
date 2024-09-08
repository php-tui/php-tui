<?php

declare(strict_types=1);

namespace PhpTui\Tui\Widget\WidgetRenderer;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

/**
 * Will iterate over all widget renderers to render the widget Each renderer
 * should return immediately if the widget is not of the correct type.
 *
 * This renderer will always pass _itself_ as the renderer to the passed in widgets
 * and so the `$renderer` parameter is unused.
 */
final class AggregateWidgetRenderer implements WidgetRenderer
{
    /**
     * @param WidgetRenderer[] $renderers
     */
    public function __construct(private readonly array $renderers)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        if ($widget instanceof WidgetRenderer) {
            $widget->render($this, $widget, $buffer, $buffer->area());

            return;
        }

        foreach ($this->renderers as $aggregateRenderer) {
            $aggregateRenderer->render($this, $widget, $buffer, $area);
        }
    }
}
