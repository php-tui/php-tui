<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

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

        $area = $this->getTrackArea($buffer->area());
    }

    private function getTrackArea(Area $area): Area
    {
    }
}
