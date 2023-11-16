<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\BlockSet;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\WidgetRenderer;

final class GaugeRenderer implements WidgetRenderer
{
    public function render(
        WidgetRenderer $renderer,
        Widget $widget,
        Buffer $buffer
    ): void {
        $area = $buffer->area();
        if (!$widget instanceof GaugeWidget) {
            return;
        }

        $buffer->setStyle($area, $widget->style);
        if ($area->height < 1) {
            return;
        }

        $pct = round($widget->ratio * 100);
        $label = $widget->label ?? Span::fromString(sprintf('%.2f%%', $pct));
        $clampedLabelWidth = min($area->width, $label->width());
        $labelCol = (int)floor($area->left() + ($area->width - $clampedLabelWidth) / 2);
        $labelRow = (int)floor($area->top() + $area->height / 2);

        $filledWidth = $area->width * $widget->ratio;
        $end = $area->left() + floor($filledWidth);

        foreach (range($area->top(), $area->bottom() - 1) as $y) {
            foreach (range($area->left(), (int)floor($end)) as $x) {

                if ($x === $area->right()) {
                    break;
                }
                $cell = $buffer->get(Position::at($x, $y));
                if ($x < $labelCol || $x > $labelCol + $clampedLabelWidth - 1 || $y != $labelRow) {
                    $cell->setChar(BlockSet::FULL);
                } else {
                    $cell->setChar(' ');
                }
            }

            if ($widget->ratio < 1) {
                $buffer->get(
                    Position::at((int)floor($end), $y)
                )->setChar($this->getUnicodeBlock(fmod($filledWidth, 1.0)));
            }
        }
        $buffer->putSpan(Position::at($labelCol, $labelRow), $label, $clampedLabelWidth);
    }

    private function getUnicodeBlock(float $frac): string
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
            default => ' ',
        };
    }
}
