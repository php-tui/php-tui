<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

final class SetRgbForegroundColor implements TermCommand
{
    public function __construct(private int $r, private int $g, private int $b)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetRgbBackgroundColor(%d, %d, %d)', $this->r, $this->g, $this->b);
    }
}
