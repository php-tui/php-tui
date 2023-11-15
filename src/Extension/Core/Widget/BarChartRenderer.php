<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Exception\TodoException;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class BarChartRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof BarChartWidget) {
            return;
        }
        $buffer->setStyle($area, $widget->style);

        if ($area->isEmpty() || $widget->data === [] || $widget->barWidth === 0) {
            return;
        }

        match($widget->direction) {
            Direction::Vertical => throw new TodoException('Not implemented yet!'),
            Direction::Horizontal => throw new TodoException('Not implemented yet!'),
        };

    }
}
