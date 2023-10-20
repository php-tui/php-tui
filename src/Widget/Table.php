<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\ItemList\HighlightSpacing;
use DTL\PhpTui\Widget\Table\TableRow;

final class Table implements Widget
{
    /**
     * @param array<int,mixed> $widths
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
        private HighlightSpacing $highlightSpacing
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
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
            header: null,
            rows: [],
            highlightSpacing: HighlightSpacing::WhenSelected,
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
}
