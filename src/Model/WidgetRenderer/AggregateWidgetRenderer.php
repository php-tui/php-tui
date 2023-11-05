<?php

namespace PhpTui\Tui\Model\WidgetRenderer;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

/**
 * Will iterate over all widget renderers to render the widget Each renderer
 * should return immediately if the widget is not of the correct type.
 *
 * This renderer will always pass _itself_ as the renderer to the passed in widgets
 * and so the `$renderer` parameter is unused.
 */
class AggregateWidgetRenderer implements WidgetRenderer
{
    /**
     * @param WidgetRenderer[] $renderers
     */
    public function __construct(private array $renderers)
    {
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        foreach ($this->renderers as $renderer) {
            $renderer->render($this, $widget, $area, $buffer);
        }
    }
}
