<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Grid;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use RuntimeException;

class GridRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof Grid) {
            return;
        }

        $layout = Layout::default()
            ->constraints($widget->constraints)
            ->direction($widget->direction)
            ->split($area);

        foreach ($widget->widgets as $index => $gridWidget) {
            if (!$layout->has($index)) {
                throw new RuntimeException(sprintf(
                    'Widget at offset %d has no corresponding constraint. ' .
                    'Ensure that the number of constraints match or exceed the number of widgets',
                    $index
                ));
            }
            $cellArea = $layout->get($index);
            $renderer->render($renderer, $gridWidget, $cellArea, $buffer);
        }
    }
}
