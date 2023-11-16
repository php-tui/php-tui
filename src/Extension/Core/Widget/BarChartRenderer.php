<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\BarChart\Bar;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
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

        $groupTicks = $this->groupTicks($widget, $area->width, $area->height);
        dd($groupTicks);
        match($widget->direction) {
            Direction::Vertical => throw new TodoException('Not implemented yet!'),
            Direction::Horizontal => throw new TodoException('Not implemented yet!'),
        };

    }

    /**
     * @return list<list<int>>
     */
    private function groupTicks(BarChartWidget $widget, int $availableSpace, int $barMaxLength): array
    {
        $max = $this->maxDataValue($widget);
        $space = $availableSpace;
        $ticks = [];

        foreach ($widget->data as $group) {
            if ($space === 0) {
                return $ticks;
            }
            $nBars = count($group->bars);
            $groupWidth = $nBars * $widget->barWidth + max(0, $nBars - 1) * $widget->barGap;

            if ($space > $groupWidth) {
                $space = max(0, $space - $groupWidth + $widget->groupGap + $widget->barGap);
            } else {
                $maxBars = ($space + $widget->barGap) / ($widget->barWidth + $widget->barGap);
                if ($maxBars > 0) {
                    $space = 0;
                    $nBars = intval($maxBars);
                } else {
                    return $ticks;
                }
            }

            $bars = $group->bars;
            if ($nBars <= 0) {
                return $ticks;
            }

            $ticks[] = array_map(function (Bar $bar) use ($barMaxLength, $max) {
                    return intval($bar->value * $barMaxLength * 8 / $max);
            }, array_slice($bars, 0, $nBars));
        }
        return $ticks;
    }

    private function maxDataValue(BarChartWidget $widget): int
    {
        if (null !== $widget->max) {
            return $widget->max;
        }

        return array_reduce($widget->data, function (int $max, BarGroup $barGroup) {
            $barGroupMax = $barGroup->max();
            if ($barGroupMax> $max) {
                return $barGroupMax;
            }
            return $max;
        }, 0);
    }
}
