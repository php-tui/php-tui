<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Widget\ItemList\HighlightSpacing;
use DTL\PhpTui\Widget\ItemList\ItemListState;
use DTL\PhpTui\Widget\Table\TableCell;
use DTL\PhpTui\Widget\Table\TableRow;
use DTL\PhpTui\Widget\Table\TableState;

final class Table implements Widget
{
    /**
     * @param array<int,Constraint> $widths
     * @param list<TableRow> $rows
     */
    public function __construct(
        private ?Block $block,
        private Style $style,
        private array $widths,
        private int $columnSpacing,
        private Style $highlightStyle,
        private string $highlightSymbol,
        private ?TableRow $header,
        private array $rows,
        private HighlightSpacing $highlightSpacing,
        private TableState $state,

    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $buffer->setStyle($area, $this->style);

        /** @var Area $tableArea */
        $tableArea = $this->block ? (function (Block $block, Area $area, Buffer $buffer): Area {
            $block->render($area, $buffer);
            return $block->inner($area);
        })($this->block, $area, $buffer) : $area;

        $selectionWidth = $this->highlightSpacing->shouldAdd(
            $this->state->selected !== null
        ) ? mb_strlen($this->highlightSymbol) : 0;

        $columnWidths = $this->getColumnsWidths($tableArea->width, intval($selectionWidth));
        $highlightSymbol = $this->highlightSymbol ?: '';
        $currentHeight = 0;
        $rowsHeight = $tableArea->height;

        $header = $this->header;
        if ($header !== null) {
            $maxHeaderHight = min($tableArea->height, $header->totalHeight());
            $buffer->setStyle(
                Area::fromPrimitives(
                    $tableArea->left(),
                    $tableArea->top(),
                    $tableArea->width,
                    min($tableArea->height, $header->height)
                ),
                $header->style,
            );
            $innerOffset = $tableArea->left();
            foreach ($columnWidths as $i => $width) {
                $cell = $header->getCell($i);
                if (null === $cell) {
                    continue;
                }
                $this->renderCell(
                    $buffer,
                    $cell,
                    Area::fromPrimitives(
                        $innerOffset + $width[0],
                        $tableArea->top(),
                        $width[1],
                        $maxHeaderHight
                    )
                );
            }
            $currentHeight += $maxHeaderHight;
            $rowsHeight = max(0, $rowsHeight - $maxHeaderHight);
        }
        if (count($this->rows) === 0) {
            return;
        }

        [$start, $end] = $this->getRowBounds($rowsHeight);
        $this->state->offset = $start;
        foreach (array_slice($this->rows, $start, $end) as $i => $tableRow) {
            [$row, $innerOffset] = [$tableArea->top() + $currentHeight, $tableArea->left()];

            $currentHeight += $tableRow->totalHeight();
            $tableRowArea = Area::fromPrimitives($innerOffset, $row, $tableArea->width, $tableRow->height);
            $buffer->setStyle($tableRowArea, $tableRow->style);
            $isSelected = $this->state->selected === $i + $start;
            if ($selectionWidth > 0 && $isSelected) {
                $buffer->putString(
                    Position::at(
                        $innerOffset,
                        $row,
                    ),
                    $highlightSymbol,
                    $tableRow->style,
                    $tableArea->width,
                );
            }
            foreach ($columnWidths as $i => $width) {
                $cell = $tableRow->getCell($i);
                if (null === $cell) {
                    continue;
                }
                $this->renderCell(
                    $buffer,
                    $cell,
                    Area::fromPrimitives(
                        $innerOffset + $width[0],
                        $row,
                        $width[1],
                        $tableRow->height,
                    )
                );
            }
            if ($isSelected) {
                $buffer->setStyle($tableRowArea, $this->highlightStyle);
            }
        }
    }

    public static function default(): self
    {
        return new self(
            block: null,
            style: Style::default(),
            widths: [],
            columnSpacing: 0,
            highlightStyle: Style::default(),
            highlightSymbol: '>>',
            highlightSpacing: HighlightSpacing::WhenSelected,
            header: null,
            rows: [],
            state: new TableState(offset: 0, selected: null)
        );
    }

    public function header(TableRow $tableRow): self
    {
        $this->header = $tableRow;
        return $this;
    }
    /**
     * @param list<TableRow> $rows
     */
    public function rows(array $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return list<array{int,int}>
     */
    private function getColumnsWidths(int $maxWidth, int $selectionWidth): array
    {
        $constraints = [
            Constraint::length($selectionWidth)
        ];
        foreach ($this->widths as $constraint) {
            $constraints[] = $constraint;
            $constraints[] = Constraint::length($this->columnSpacing);
        }
        if (0 !== count($this->widths)) {
            array_pop($constraints);
        }
        $chunks = Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints($constraints)
            ->split(Area::fromDimensions($maxWidth, 1));

        $widths = [];

        // iterate over the specified width constraints
        for ($i = 1; $i < count($constraints); $i += 2) {
            $chunk = $chunks->get($i);
            $widths[] = [$chunk->position->x, $chunk->width];
        }
        return $widths;
    }

    /**
     * @param Constraint[] $widths
     */
    public function widths(array $widths): self
    {
        $this->widths = $widths;
        return $this;
    }

    private function renderCell(Buffer $buffer, TableCell $cell, Area $area): void
    {
        $buffer->setStyle($area, $cell->style);
        foreach ($cell->content->lines as $i => $line) {
            if ($i >= $area->height) {
                break;
            }

            $xOffset = match ($line->alignment) {
                HorizontalAlignment::Center => ($area->width / 2) - max(0, ($line->width() / 2)),
                HorizontalAlignment::Right => max($area->width - 0, $line->width()),
                default => 0,
            };
            $buffer->putLine(
                Position::at($area->position->x + $xOffset, $area->position->y + $i),
                $line,
                $area->width
            );
        }
    }

    /**
     * TODO: This is the same as ItemList except for row/item which can
     *       be refactored to implement an interface.
     *
     * @return array{int,int}
     */
    private function getRowBounds(int $maxHeight): array
    {
        $offset = min($this->state->offset, max(count($this->rows) - 1, 0));
        $start = $end = $offset;
        $height = 0;
        foreach (array_slice($this->rows, $start) as $row) {
            if ($height + $row->height() > $maxHeight) {
                break;
            }
            $height += $row->height();
            $end += 1;
        }

        if ($this->state->selected !== null) {
            $selected = min(count($this->rows) - 1, $this->state->selected ?? 0);
            while ($selected >= $end) {
                $height = $height += $this->rows[$end]->height();
                $end += 1;
                while ($height > $maxHeight) {
                    $height = max(0, $height - $this->rows[$start]->height());
                    $start += 1;
                }
            }
            while ($selected < $start) {
                $start -= 1;
                $height = $height += $this->rows[$start]->height();
                while ($height > $maxHeight) {
                    $end -= 1;
                    $height = max(0, $height - $this->rows[$end]->height());
                }
            }
        }

        return [$start, $end];
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

    public function block(Block $block): self
    {
        $this->block = $block;
        return $this;
    }

    public function state(TableState $state): self
    {
        $this->state = $state;
        return $this;
    }
}
