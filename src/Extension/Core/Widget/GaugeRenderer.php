<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Position\FractionalPosition;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Symbol\BlockSet;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class GaugeRenderer implements WidgetRenderer
{
    public function render(
        WidgetRenderer $renderer,
        Widget $widget,
        Buffer $buffer,
        Area $area,
    ): void {
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
                if ($y < 0) {
                    break;
                }
                $cell = $buffer->get(Position::at($x, $y));
                if ($x < $labelCol || $x > $labelCol + $clampedLabelWidth - 1 || $y != $labelRow) {
                    $cell->setChar(BlockSet::FULL);
                    $cell->setStyle($widget->style->atPosition(FractionalPosition::at(
                        ($x - $area->left()) / $area->width,
                        ($y - $area->top()) / $area->height,
                    )));
                } else {
                    $cell->setChar(' ');
                    $cell->setStyle(
                        Style::default()
                            ->bg($widget->style->fg ?? AnsiColor::Reset)
                            ->fg($widget->style->bg ?? AnsiColor::Reset),
                    );
                }
            }

            if ($widget->ratio < 1 && $y >= 0) {
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
