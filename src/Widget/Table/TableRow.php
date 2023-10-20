<?php

namespace DTL\PhpTui\Widget\Table;

use DTL\PhpTui\Model\Style;

final class TableRow
{
    /**
     * @param list<TableCell> $cells
     */
    private function __construct(
        public array $cells,
        public int $height,
        public int $bottomMargin,
        public Style $style,
    ) {
    }

    /**
     * @param list<TableCell> $cells
     */
    public static function fromCells(array $cells): self
    {
        return new self($cells, 1, 0, Style::default());
    }

    public function totalHeight(): int
    {
        return $this->height + $this->bottomMargin;
    }

    public function getCell(int $index): ?TableCell
    {
        if (!isset($this->cells[$index])) {
            return null;
        }
        return $this->cells[$index];
    }

    public function height(): int
    {
        return $this->height;
    }
}
