<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\WidgetRenderer;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetSet;

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

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        foreach ($this->renderers as $aggregateRenderer) {
            $aggregateRenderer->render($this, $widget, $buffer);
        }
    }

    public static function fromWidgetSets(WidgetSet ...$widgetSets): self
    {
        $renderers = [];
        foreach ($widgetSets as $widgetSet) {
            foreach ($widgetSet->renderers() as $renderer) {
                $renderers[] = $renderer;
            }
        }

        return new self($renderers);
    }
}
