<?php

namespace DTL\PhpTui\Widget\Table;

final class TableRow
{
    /**
     * @param list<TableCell> $cells
     */
    private function __construct(private array $cells)
    {
    }

    /**
     * @param list<TableCell> $cells
     */
    public static function fromCells(array $cells): self
    {
        return new self($cells);
    }
}
