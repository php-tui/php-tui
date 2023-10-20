<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Corner;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\Table\HighlightSpacing;
use DTL\PhpTui\Widget\Table\TableItem;
use DTL\PhpTui\Widget\Table\TableState;

/**
 * Port of the Ratatui List - which is a reserved word in PHP
 */
class Table implements Widget
{
    /**
     * @param list<TableItem> $items
     */
    public function __construct(
        private ?Block $block,
        private array $items,
        private Style $style,
        private Corner $startCorner,
        private Style $highlightStyle,
        private string $highlightSymbol,
        private TableState $state,
        private HighlightSpacing $highlightSpacing,
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $buffer->setStyle($area, $this->style);

        /** @var Area $listArea */
        $listArea = $this->block ? (function (Block $block, Area $area, Buffer $buffer): Area {
            $block->render($area, $buffer);
            return $block->inner($area);
        })($this->block, $area, $buffer) : $area;

        if ($listArea->width < 1 || $listArea->height < 1) {
            return;
        }

        if (count($this->items) === 0) {
            return;
        }
        $listHeight = $listArea->height;
        [$start, $end] = $this->getItemsBounds($listHeight);
        $this->state->offset = $start;
        $highlightSymbol = $this->highlightSymbol ?? '';
        $blankSymbol = str_repeat(' ', mb_strlen($highlightSymbol));
        $currentHeight = 0;
        $selectionSpacing = $this->highlightSpacing->shouldAdd($this->state->selected !== null);
        foreach (array_slice($this->items, $start, $end-$start) as $i => $item) {
            /** @var TableItem $item */
            if ($i === $this->state->offset) {
                continue;
            }

            [$x, $y, $currentHeight] = (function () use ($item, $listArea, $currentHeight) {
                if ($this->startCorner === Corner::BottomLeft) {
                    $currentHeight += $item->height();
                    return [$listArea->left(), $listArea->bottom() - $currentHeight, $currentHeight];
                }

                $pos = [$listArea->left(), $listArea->top() + $currentHeight, $currentHeight];
                $currentHeight += $item->height();
                return $pos;
            })();

            $area = Area::fromPrimitives($x, $y, $listArea->width, $item->height());
            $itemStyle = $this->style->patch($item->style);
            $buffer->setStyle($area, $itemStyle);

            $isSelected = $this->state->selected === $i;
            foreach ($item->content->lines as $j => $line) {
                $symbol = $isSelected && $j === 0 ?
                    $highlightSymbol :
                    $blankSymbol
                ;

                [$elemPosition, $maxElementWidth] = (function () use ($listArea, $selectionSpacing, $buffer, $x, $j, $y, $symbol, $itemStyle) {
                    if ($selectionSpacing === true) {
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
                    $buffer->setStyle($area, $this->highlightStyle);
                }
            }
        }

    }

    /**
     * @return array{int,int}
     */
    private function getItemsBounds(int $maxHeight): array
    {
        $offset = min($this->state->offset, max(count($this->items) - 1, 0));
        $start = $end = $offset;
        $height = 0;
        foreach ($this->items as $i => $item) {
            if ($i === $offset) {
                continue;
            }

            if ($height + $item->height() > $maxHeight) {
                break;
            }
            $height += $item->height();
            $end += 1;
        }

        $selected = min(count($this->items) - 1, $this->state->selected ?? 0);
        while ($selected >= $end) {
            $height = $height += $this->items[$end]->height();
            $end += 1;
            while ($height > $maxHeight) {
                $height = max(0, $height - $this->items[$start]->height());
                $start += 1;
            }
        }
        while ($selected < $start) {
            $start -= 1;
            $height = $height += $this->items[$start]->height();
            while ($height > $maxHeight) {
                $end -= 1;
                $height = max(0, $height - $this->items[$end]->height());
            }
        }

        return [$start, $end];

    }
}
