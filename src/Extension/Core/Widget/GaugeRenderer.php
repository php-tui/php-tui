<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\Widget\BlockSet;
use PhpTui\Tui\Model\Widget\Span;

final class GaugeRenderer implements WidgetRenderer
{
    public function render(
        WidgetRenderer $renderer,
        Widget $widget,
        Area $area,
        Buffer $buffer
    ): void
    {
        if (!$widget instanceof GaugeWidget) {
            return;
        }

        $buffer->setStyle($area, $widget->style);
        if ($area->height < 1) {
            return;
        }

        $pct = round($widget->ratio * 100);
        $label = $widget->label ?? Span::fromString(sprintf('%.2f', $pct));
        $clampedLabelWidth = min($area->width, $label->width());
        $labelCol = (int)floor($area->left() + ($area->width - $clampedLabelWidth) / 2);
        $labelRow = (int)floor($area->top() + $area->height / 2);

        $filledWidth = $area->width * $widget->ratio;
        $end = $widget->useUnicode ? 
            $area->left() + floor($filledWidth) : 
            floor($area->left() + $filledWidth);


        foreach (range($area->top(), $area->bottom() - 1) as $y) {
            foreach (range($area->left(),(int)floor($end)) as $x) {
                $cell = $buffer->get(Position::at($x, $y));
                if ($x < $labelCol || $x > $labelCol + $clampedLabelWidth || $y != $labelRow) {
                    $cell->setChar(BlockSet::FULL);
                } else {
                    $cell->setChar(' ');
                }
            }

            if ($widget->useUnicode) {
                $buffer->get(
                    Position::at((int)floor($end), $y)
                )->setChar($this->getUnicodeBlock($filledWidth % 1.0));
            }
        }
        $buffer->putSpan(Position::at($labelCol, $labelRow), $label, $clampedLabelWidth);
    }

    private function getUnicodeBlock(int $frac): string
    {
        return match ((int)floor($frac * 8.0)) {
            1 => BlockSet::ONE_EIGHTH,
            2 => BlockSet::ONE_QUARTER,
            3 => BlockSet::THREE_EIGHTHS,
            4 => BlockSet::HALF,
            5 => BlockSet::FIVE_EIGHTHS,
            6 => BlockSet::THREE_QUARTERS,
            7 => BlockSet::SEVEN_EIGHTHS,
            8 => BlockSet::FULL,
            default => " ",
        };
    }
}
