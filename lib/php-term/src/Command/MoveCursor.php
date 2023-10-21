<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

final class MoveCursor implements TermCommand
{
    public function __construct(public readonly int $line, public readonly int $col)
    {
    }

    public function __toString(): string
    {
        return sprintf('MoveCursor(line=%d,col=%d)', $this->line, $this->col);
    }
}
