<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Layout\Layout;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use RuntimeException;

class GridRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof GridWidget) {
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
            $subBuffer = Buffer::empty($cellArea);
            $renderer->render($renderer, $gridWidget, $subBuffer);
            $buffer->putBuffer($cellArea->position, $subBuffer);
        }
    }
}
