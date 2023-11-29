<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class TabsRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        if (!$widget instanceof TabsWidget) {
            return;
        }
        $area = $buffer->area();
        $buffer->setStyle($area, $widget->style);
        if ($area->height < 1) {
            return;
        }

        $x = $area->left();
        $titlesCount = count($widget->titles);
        $i = 0;
        foreach ($widget->titles as $title) {
            $isLastTitle = $titlesCount - 1 === $i;
            $x+=1;
            $remainingWidth = max(0, $area->right() - $x);
            $i++;
        }

    }
}
