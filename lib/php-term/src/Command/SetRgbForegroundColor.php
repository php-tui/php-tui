<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

final class SetRgbForegroundColor implements TermCommand
{
    public function __construct(public readonly int $r, public readonly int $g, public readonly int $b)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetRgbBackgroundColor(%d, %d, %d)', $this->r, $this->g, $this->b);
    }
}
