<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Widget\ItemList\HighlightSpacing;
use PhpTui\Tui\Widget\Table\TableCell;
use PhpTui\Tui\Widget\Table\TableRow;
use PhpTui\Tui\Widget\Table\TableState;

/**
 * Shows tabular data arranged in columns. The column spacing is determined by
 * the "width" constraints.
 */
final class Table implements Widget
{
    public function __construct(
        /**
         * Style of the area occupied by the table.
         */
        private Style $style,
        /**
         * Constraints to use to determine the column widths.
         * @var list<\PhpTui\Tui\Model\Constraint>
         */
        private array $widths,
        /**
         * Spacing to enforce between columns.
         */
        private int $columnSpacing,
        /**
         * Style used when a row is highlighted.
         */
        private Style $highlightStyle,
        /**
         * Symbol to show when the row is highlighted.
         */
        private string $highlightSymbol,
        /**
         * Optional header.
         */
        private ?TableRow $header,
        /**
         * Table rows.
         * @var list<\PhpTui\Tui\Widget\Table\TableRow>
         */
        private array $rows,
        /**
         * Highlight spacing strategy.
         */
        private HighlightSpacing $highlightSpacing,

        /**
         * Hold the state of the table (i.e. selected row, current offset).
         */
        private TableState $state,
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $buffer->setStyle($area, $this->style);

        $tableArea = $area;
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
        foreach (array_slice($this->rows, $start, $end - $start) as $i => $tableRow) {
            [$rowY, $innerOffset] = [$tableArea->top() + $currentHeight, $tableArea->left()];

            $currentHeight += $tableRow->totalHeight();
            $tableRowArea = Area::fromPrimitives($innerOffset, $rowY, $tableArea->width, $tableRow->height);
            $buffer->setStyle($tableRowArea, $tableRow->style);
            $isSelected = $this->state->selected === $i + $start;
            if ($selectionWidth > 0 && $isSelected) {
                $buffer->putString(
                    Position::at(
                        $innerOffset,
                        $rowY,
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
                        $rowY,
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
    public function rows(TableRow ...$rows): self
    {
        $this->rows = array_values($rows);
        return $this;
    }

    public function widths(Constraint ...$widths): self
    {
        $this->widths = array_values($widths);
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

    public function state(TableState $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function highlightSymbol(string $symbol): self
    {
        $this->highlightSymbol = $symbol;
        return $this;
    }

    public function highlightStyle(Style $style): self
    {
        $this->highlightStyle = $style;
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
            if ($height + $row->height > $maxHeight) {
                break;
            }
            $height += $row->height;
            $end += 1;
        }

        if ($this->state->selected !== null) {
            $selected = min(count($this->rows) - 1, $this->state->selected ?? 0);
            while ($selected >= $end) {
                $height = $height += $this->rows[$end]->height;
                $end += 1;
                while ($height > $maxHeight) {
                    $height = max(0, $height - $this->rows[$start]->height);
                    $start += 1;
                }
            }
            while ($selected < $start) {
                $start -= 1;
                $height = $height += $this->rows[$start]->height;
                while ($height > $maxHeight) {
                    $end -= 1;
                    $height = max(0, $height - $this->rows[$end]->height);
                }
            }
        }

        return [$start, $end];
    }
}
