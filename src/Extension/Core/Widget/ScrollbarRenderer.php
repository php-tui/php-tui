<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class ScrollbarRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        //
        // For ScrollbarOrientation::VerticalRight
        //
        //                   ┌───────── track_axis  (x)
        //                   v
        //   ┌───────────────┐
        //   │               ║<──────── track_start (y1)
        //   │               █
        //   │               █
        //   │               ║
        //   │               ║<──────── track_end   (y2)
        //   └───────────────┘
        //
        // For ScrollbarOrientation::HorizontalBottom
        //
        //   ┌───────────────┐
        //   │               │
        //   │               │
        //   │               │
        //   └═══███═════════┘<──────── track_axis  (y)
        //    ^             ^
        //    │             └────────── track_end   (x2)
        //    │
        //    └──────────────────────── track_start (x1)
        //
        if (!$widget instanceof ScrollbarWidget) {
            return;
        }

        $area = $this->getTrackArea($widget, $buffer->area());
    }

    private function getTrackArea(ScrollbarWidget $widget, Area $area): Area
    {
        $area = (static function (Area $area, ScrollbarWidget $widget) {
            if ($widget->beginSymbol !== null) {
                if ($widget->isVertical()) {
                    return Area::fromScalars(
                        $area->position->x,
                        $area->position->y,
                        $area->width,
                        max(0, $area->height - 1)
                    );
                }

                return Area::fromScalars(
                    $area->position->x,
                    $area->position->y,
                    max(0, $area->width - 1),
                    $area->height
                );
            }

            return $area;
        })($area, $widget);

        if ($widget->endSymbol === null) {
            return $area;
        }

        if ($widget->isVertical()) {
            return Area::fromScalars(
                $area->position->x,
                $area->position->y,
                $area->width,
                max(0, $area->height - 1)
            );
        }

        return $area;
    }
}
