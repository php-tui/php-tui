<?php

namespace PhpTui\Tui\Model\WidgetRenderer;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

class AggregateWidgetRenderer implements WidgetRenderer
{
    /**
     * @param WidgetRenderer[] $renderers
     */
    public function __construct(private array $renderers)
    {
    }

    public function render(Widget $widget, Area $area, Buffer $buffer): void
    {
        foreach ($this->renderers as $renderer) {
            $renderer->render($widget, $area, $buffer);
        }
    }
}
