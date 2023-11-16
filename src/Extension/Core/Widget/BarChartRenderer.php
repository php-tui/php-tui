<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\BarChart\Bar;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChart\LabelInfo;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Exception\TodoException;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\Widget\BarSet;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;

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
            Direction::Vertical => $this->renderVertical($widget, $buffer, $area),
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

    private function renderVertical(BarChartWidget $widget, Buffer $buffer, Area $area): void
    {
        $labelInfo = $this->labelInfo($widget, $area->height - 1);
        $barsArea = Area::fromScalars(
            $area->position->x,
            $area->position->y,
            $area->width,
            $area->height - $labelInfo->height
        );

        $groupTicks = $this->groupTicks($widget, $area->width, $area->height);
        $this->renderVerticalBars($widget, $buffer, $barsArea, $groupTicks);
        $this->renderLabelsAndVAlues($widget, $area, $buffer, $labelInfo, $groupTicks);

    }

    private function labelInfo(BarChartWidget $widget, int $availableHeight): LabelInfo
    {
        if ($availableHeight === 0) {
            return new LabelInfo(groupLabelVisible: false, barLabelVisible: false, height: 0);
        }

        $barLabelVisible = $widget->isBarLabelVisible();

        if ($availableHeight === 1 && $barLabelVisible) {
            return new LabelInfo(
                groupLabelVisible: false,
                barLabelVisible: true,
                height: 1
            );
        }

        $groupLabelVisible = $widget->isGroupLabelVisible();

        return new LabelInfo(
            $groupLabelVisible,
            $barLabelVisible,
            ($groupLabelVisible ? 1 : 0) + ($barLabelVisible ? 1 : 0),
        );

    }

    /**
     * @param array<int,array<int,int>> $groupTicks
     */
    private function renderVerticalBars(BarChartWidget $widget, Buffer $buffer, Area $area, array $groupTicks): void
    {
        $barX = $area->left();
        foreach ($widget->data as $i => $group) {
            $ticksList = $groupTicks[$i];
            foreach ($group->bars as $ii => $bar) {
                $ticks = $ticksList[$ii];
                for ($j = $area->height - 1; $j >= 0; $j--) {
                    $symbol = BarSet::fromIndex($ticks);

                    $barStyle = $widget->barStyle->patch($bar->style);

                    for ($x = 0; $x < $widget->barWidth; $x++) {
                        $cell = $buffer->get(Position::at($barX + $x, $area->top() + $j));
                        $cell->setChar($symbol);
                        $cell->setStyle($barStyle);
                    }

                    $ticks = max(0, $ticks - 8);
                }
                $barX += $widget->barGap + $widget->barWidth;
            }
            $barX += $widget->groupGap;
        }
    }

    /**
     * @param array<int,array<int,int>> $groupTicks
     */
    private function renderLabelsAndVAlues(BarChartWidget $widget, Area $area, Buffer $buffer, LabelInfo $labelInfo, array $groupTicks)
    {
        $barX = $area->left();
        $barY = $area->bottom() - $labelInfo->height - 1;

        foreach ($widget->data as $i => $group) {
            if ([] === $group->bars) {
                continue;
            }
            $tickList = $groupTicks[$i];
            $bars = $group->bars;

            // print group labels under the bars or the previous labels
            if ($labelInfo->groupLabelVisible) {
                $labelMaxWidth = count($tickList) * ($widget->barWidth + $widget->barGap) - $widget->barGap;
                $groupArea = Area::fromScalars(
                    $barX,
                    $area->bottom() - 1,
                    $labelMaxWidth,
                    1,
                );
                $this->renderGroupLabel($group, $buffer, $groupArea, $widget->labelStyle);
            }

            foreach ($group->bars as $ii => $bar) {
                $ticks = $tickList[$ii];
                if ($labelInfo->barLabelVisible) {
                    $this->renderBarLabel($bar, $buffer, $widget->barWidth, $barX, $barY + 1, $widget->labelStyle);
                }
                $this->renderBarValue($bar, $buffer, $widget->barWidth, $barX, $barY, $widget->valueStyle, $ticks);
            }
        }
    }

    private function renderGroupLabel(BarGroup $group, Buffer $buffer, Area $area, Style $style): void
    {
        $label = $group->label;
        if ($label === null) {
            return;
        }

        foreach ($label->spans as $span) {
            $span->style = $style->patch($span->style);
        }

        $xOffset = match ($label->alignment) {
            HorizontalAlignment::Center => max(0, $area->width - $label->width()) >> 1,
            HorizontalAlignment::Right => max(0, $area->width - $label->width()),
            default => 0,
        };

        $buffer->putLine(
            $area->position->withX($area->position->x + $xOffset),
            $label,
            $area->width
        );
    }

    private function renderBarLabel(Bar $bar, Buffer $buffer, int $maxWidth, int $x, int $y, Style $defaultStyleLabel): void
    {
        $label = $bar->label;
        if (null === $label) {
            return;
        }

        foreach ($label->spans as $span) {
            $span->style = $defaultStyleLabel->patch($span->style);
        }

        $buffer->putLine(
            Position::at(
                $x + max(0, $maxWidth - $label->width()) >> 1,
                $y,
            ),
            $label,
            $maxWidth
        );
    }

    private function renderBarValue(Bar $bar, Buffer $buffer, int $maxWidth, int $x, int $y, Style $defaultValueStyle, int $ticks): void
    {
        if ($bar->value === 0) {
            return;
        }
        $valueLabel = $bar->textValue ? $bar->textValue : (string)$bar->value;
        $width = mb_strlen($valueLabel);
        if ($width < $maxWidth || ($width === $maxWidth && $ticks >= 8)) {
            // why strlen? Ratatui does value_label.len() not sure why.
            $buffer->putString(
                Position::at(
                    $x + (max(0, $maxWidth - strlen($valueLabel)) >> 1),
                    $y,
                ),
                $valueLabel,
                $defaultValueStyle->patch($bar->valueStyle),
            );
        }
    }
}
