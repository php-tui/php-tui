<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

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
            $x += 1;
            $remainingWidth = max(0, $area->right() - $x);
            if ($remainingWidth === 0) {
                break;
            }
            $pos = $buffer->putLine(Position::at($x, $area->top()), $title, $remainingWidth);

            if ($i === $widget->selected) {
                $buffer->setStyle(Area::fromScalars($x, $area->top(), max(0, $pos->x - $x), 1), $widget->highlightStyle);
            }
            $x = $pos->x + 1;

            $remainingWidth = max(0, $area->right() - $x);
            if ($remainingWidth === 0 || $isLastTitle) {
                break;
            }
            $pos = $buffer->putSpan(Position::at($x, $area->top()), $widget->divider, $remainingWidth);
            $x = $pos->x;

            $i++;
        }

    }
}
