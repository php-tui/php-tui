<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

final class MoveCursor implements TermCommand
{
    public function __construct(private int $line, private int $col)
    {
    }

    public function __toString(): string
    {
        return sprintf('MoveCursor(line=%d,col=%d)', $this->line, $this->col);
    }
}
