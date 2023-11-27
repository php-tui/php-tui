<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Position\FractionalPosition;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Symbol\BlockSet;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class GaugeRenderer implements WidgetRenderer
{
    public function render(
        WidgetRenderer $renderer,
        Widget $widget,
        Buffer $buffer
    ): void {
        $area = $buffer->area();
        if (!$widget instanceof GaugeWidget || $area->height < 1) {
            return;
        }

        $buffer->setStyle($area, $widget->style);
        $label = $widget->label ?? Span::fromString(sprintf('%.2f%%', round($widget->ratio * 100)));
        $clampedLabelWidth = min($area->width, $label->width());
        $labelRow = (int)floor($area->top() + $area->height / 2);
        $labelCol = (int)floor($area->left() + ($area->width - $clampedLabelWidth) / 2);

        $filledWidth = $area->width * $widget->ratio;
        $end = (int) ($area->left() + floor($filledWidth));

        foreach (range($area->top(), $area->bottom() - 1) as $y) {
            foreach (range($area->left(), $area->right() - 1) as $x) {
                if ($x === $area->right()) {
                    break;
                }
                $cell = $buffer->get(Position::at($x, $y));

                // Determine if the cell is part of the empty portion of the gauge
                $isEmptyPortion = $x > $end;
                // Determine if the cell is part of the label
                $isLabelArea = $x >= $labelCol && $x < ($labelCol + $clampedLabelWidth) && $y === $labelRow;

                if ($isEmptyPortion) {
                    // Draw the empty portion of the gauge only if the widget has a background color
                    if (!$isLabelArea && $widget->style->bg !== null) {
                        $cell->setChar(BlockSet::FULL);
                        $cell->setStyle(Style::default()->fg($widget->style->bg));
                    }
                } elseif (!$isLabelArea) {
                    // Draw the filled portion of the gauge
                    $cell->setChar(BlockSet::FULL);
                    $cell->setStyle($widget->style->atPosition(FractionalPosition::at(
                        ($x - $area->left()) / $area->width,
                        ($y - $area->top()) / $area->height,
                    )));
                } else {
                    // Spaces for the part that is covered by the label
                    $cell->setChar(' ');
                }
            }

            // Draw a "partially filled cell" if the ratio is not a whole number
            if ($widget->ratio < 1.0) {
                $buffer->get(Position::at($end, $y))
                    ->setChar($this->getUnicodeBlock(fmod($filledWidth, 1.0)));
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
