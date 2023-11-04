<?php

namespace PhpTui\Term\Painter;

use PhpTui\Term\Action\MoveCursor;
use PhpTui\Term\Action\PrintString;
use PhpTui\Term\Painter;

class StringPainter implements Painter
{
    /**
     * @var array<int,array<int,string>>
     */
    private array $grid = [];

    private int $cursorX = 0;
    private int $cursorY = 0;

    public function paint(array $actions): void
    {
        foreach ($actions as $action) {
            if ($action instanceof PrintString) {
                $this->printString($action);
            }
            if ($action instanceof MoveCursor) {
                $this->cursorX = $action->col - 1;
                $this->cursorY = $action->line - 1;
            }
        }
    }

    public function toString(): string
    {
        if (empty($this->grid)) {
            return '';
        }
        $maxX = max(
            0,
            ...array_map(
                fn (array $cells) => max(
                    array_keys($cells)
                ),
                $this->grid
            )
        );
        $lines = [];
        foreach ($this->grid as $line => &$cells) {
            for ($i = 0; $i <= $maxX; $i++) {
                if (!isset($cells[$i])) {
                    $cells[$i] = ' ';
                }
            }
            ksort($cells);
            $lines[] = implode('', $cells);
        }

        return implode("\n", $lines);
    }

    private function printString(PrintString $action): void
    {
        foreach (mb_str_split($action->string) as $char) {
            $this->paintChar($this->cursorX, $this->cursorY, $char);
            $this->cursorX++;
        }
    }

    private function paintChar(int $x, int $y, string $char): void
    {
        if (!isset($this->grid[$y][$x])) {
            $this->grid[$y][$x] = ' ';
        }
        $this->grid[$y][$x] = $char;
    }
}
