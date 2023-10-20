<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Corner;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\ItemList\HighlightSpacing;
use DTL\PhpTui\Widget\ItemList\ListItem;
use DTL\PhpTui\Widget\ItemList\ItemListState;

/**
 * Port of the Ratatui List - which is a reserved word in PHP
 */
class ItemList implements Widget
{
    /**
     * @param list<ListItem> $items
     */
    public function __construct(
        private ?Block $block,
        private array $items,
        private Style $style,
        private Corner $startCorner,
        private Style $highlightStyle,
        private string $highlightSymbol,
        private ItemListState $state,
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
            [$x, $y, $currentHeight] = (function () use ($item, $listArea, $currentHeight) {
                if ($this->startCorner === Corner::BottomLeft) {
                    $currentHeight += $item->height();
                    return [$listArea->left(), $listArea->bottom() - $currentHeight, $currentHeight];
                }

                $y = $listArea->top() + $currentHeight;
                $currentHeight += $item->height();
                return [$listArea->left(), $y, $currentHeight];
            })();

            $area = Area::fromPrimitives($x, $y, $listArea->width, $item->height());
            $itemStyle = $this->style->patch($item->style);
            $buffer->setStyle($area, $itemStyle);

            $isSelected = $this->state->selected === $i + $start;
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

    public static function default(): self
    {
        return new self(
            block: null,
            items: [],
            style: Style::default(),
            startCorner: Corner::TopLeft,
            highlightStyle: Style::default(),
            highlightSymbol: '>>',
            state: new ItemListState(0, null),
            highlightSpacing: HighlightSpacing::WhenSelected,
        );
    }

    /**
     * @param list<ListItem> $items
     */
    public function items(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function startCorner(Corner $corner): self
    {
        $this->startCorner = $corner;
        return $this;
    }

    public function select(int $selection): self
    {
        $this->state->selected = $selection;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->state->offset = $offset;
        return $this;
    }

    /**
     * @return array{int,int}
     */
    private function getItemsBounds(int $maxHeight): array
    {
        $offset = min($this->state->offset, max(count($this->items) - 1, 0));
        $start = $end = $offset;
        $height = 0;
        foreach (array_slice($this->items, $start) as $item) {
            if ($height + $item->height() > $maxHeight) {
                break;
            }
            $height += $item->height();
            $end += 1;
        }

        if ($this->state->selected !== null) {
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
        }

        return [$start, $end];

    }

    public function state(ItemListState $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function block(Block $block): self
    {
        $this->block = $block;
        return $this;
    }
}
