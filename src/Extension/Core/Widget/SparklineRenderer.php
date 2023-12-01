<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Sparkline\RenderDirection;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Math\VectorUtil;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Symbol\BarSet;
use PhpTui\Tui\Model\Widget\Widget;
use PhpTui\Tui\Model\Widget\WidgetRenderer;

final class SparklineRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        if (!$widget instanceof SparklineWidget) {
            return;
        }

        $area = $buffer->area();
        if ($area->height < 1) {
            return;
        }
        $max = $widget->max ?? VectorUtil::max($widget->data) ?? 0;
        $maxIndex = min($area->width, count($widget->data));
        $data = array_map(function (int $e) use ($max, $area): int {
            if ($max === 0) {
                return 0;
            }

            return (int) (round($e * $area->height * 8 / $max));
        }, array_slice($widget->data, 0, $maxIndex));

        for ($j = $area->height - 1; $j >= 0; $j--) {
            $i = 0;
            foreach ($data as &$value) {
                $symbol = match ($value) {
                    0 => BarSet::EMPTY,
                    1 => BarSet::ONE_EIGHTH,
                    2 => BarSet::ONE_QUARTER,
                    3 => BarSet::THREE_EIGHTHS,
                    4 => BarSet::HALF,
                    5 => BarSet::FIVE_EIGHTHS,
                    6 => BarSet::THREE_QUARTERS,
                    7 => BarSet::SEVEN_EIGHTHS,
                    default => BarSet::FULL,
                };
                $x = match ($widget->direction) {
                    RenderDirection::LeftToRight => $area->left() + $i,
                    RenderDirection::RightToLeft => max(0, $area->right() - $i - 1),
                };
                $buffer->get(Position::at(
                    $x,
                    $area->top() + $j
                ))
                    ->setChar($symbol)
                    ->setStyle($widget->style);

                $value = $value > 8 ? $value - 8 : 0;

                $i++;
            }
        }
    }
}
