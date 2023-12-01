<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Widget\Corner;
use PhpTui\Tui\Model\Widget\Widget;
use PhpTui\Tui\Model\Widget\WidgetRenderer;

final class ListRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $area = $buffer->area();
        if (!$widget instanceof ListWidget) {
            return;
        }
        $buffer->setStyle($area, $widget->style);

        /** @var Area $listArea */
        $listArea = $area;

        if ($listArea->width < 1 || $listArea->height < 1) {
            return;
        }

        if ($widget->items === []) {
            return;
        }
        $listHeight = $listArea->height;
        [$start, $end] = $this->getItemsBounds($widget, $listHeight);
        $widget->state->offset = $start;
        $highlightSymbol = $widget->highlightSymbol;
        $blankSymbol = str_repeat(' ', mb_strlen($highlightSymbol));
        $currentHeight = 0;
        $selectionSpacing = $widget->highlightSpacing->shouldAdd($widget->state->selected !== null);
        foreach (array_slice($widget->items, $start, $end - $start) as $i => $item) {
            /** @var int<0,max> $y */
            [$x, $y, $currentHeight] = (function () use ($item, $listArea, $currentHeight, $widget): array {
                if ($widget->startCorner === Corner::BottomLeft) {
                    $currentHeight += $item->height();

                    return [$listArea->left(), max(0, $listArea->bottom() - $currentHeight), $currentHeight];
                }

                $y = $listArea->top() + $currentHeight;
                $currentHeight += $item->height();

                return [$listArea->left(), $y, $currentHeight];
            })();

            $area = Area::fromScalars($x, $y, $listArea->width, $item->height());
            $itemStyle = $widget->style->patchStyle($item->style);
            $buffer->setStyle($area, $itemStyle);

            $isSelected = $widget->state->selected === $i + $start;
            /**
             * @var int<0,max> $j
             */
            foreach ($item->content->lines as $j => $line) {
                $symbol = $isSelected && $j === 0 ?
                    $highlightSymbol :
                    $blankSymbol
                ;

                [$elemPosition, $maxElementWidth] = (function () use ($listArea, $selectionSpacing, $buffer, $x, $j, $y, $symbol, $itemStyle): array {
                    if ($selectionSpacing) {
                        $pos = $buffer->putString(
                            Position::at($x, $y + $j),
                            $symbol,
                            $itemStyle,
                            $listArea->width,
                        );

                        return [Position::at($pos->x, $y + $j), ($listArea->width - ($pos->x - $x))];
                    }

                    return [Position::at($x, $y + $j), $listArea->width];
                })();
                $buffer->putLine($elemPosition, $line, $maxElementWidth);
                if ($isSelected) {
                    $buffer->setStyle($area, $widget->highlightStyle);
                }
            }
        }

    }

    /**
     * @return array{int,int}
     */
    private function getItemsBounds(ListWidget $list, int $maxHeight): array
    {
        $offset = min($list->state->offset, max(count($list->items) - 1, 0));
        $start = $end = $offset;
        $height = 0;
        foreach (array_slice($list->items, $start) as $item) {
            if ($height + $item->height() > $maxHeight) {
                break;
            }
            $height += $item->height();
            $end += 1;
        }

        if ($list->state->selected !== null) {
            if ($list->state->selected < 0) {
                $list->state->selected = 0;
            }

            $selected = min(count($list->items) - 1, $list->state->selected ?? 0);
            while ($selected >= $end) {
                $height = $height += $list->items[$end]->height();
                $end += 1;
                while ($height > $maxHeight) {
                    $height = max(0, $height - $list->items[$start]->height());
                    $start += 1;
                }
            }
            while ($selected < $start) {
                $start -= 1;
                $height = $height += $list->items[$start]->height();
                while ($height > $maxHeight) {
                    $end -= 1;
                    $height = max(0, $height - $list->items[$end]->height());
                }
            }
        }

        return [$start, $end];
    }
}
