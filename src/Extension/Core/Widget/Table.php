<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Extension\Core\Widget\ItemList\HighlightSpacing;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\Table\TableState;

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
        public Style $style,
        /**
         * Constraints to use to determine the column widths.
         * @var list<Constraint>
         */
        public array $widths,
        /**
         * Spacing to enforce between columns.
         */
        public int $columnSpacing,
        /**
          * Style used when a row is highlighted.
          */
        public Style $highlightStyle,
        /**
         * Symbol to show when the row is highlighted.
         */
        public string $highlightSymbol,
        /**
         * Optional header.
         */
        public ?TableRow $header,
        /**
         * Table rows.
         * @var list<TableRow>
         */
        public array $rows,
        /**
         * Highlight spacing strategy.
         */
        public HighlightSpacing $highlightSpacing,

        /**
         * Hold the state of the table (i.e. selected row, current offset).
         */
        public TableState $state,
    ) {
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
}
