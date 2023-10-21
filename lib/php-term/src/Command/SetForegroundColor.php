<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;

final class SetForegroundColor implements TermCommand
{
    public function __construct(private TermColor $termColor)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetForegroundColor(%s)', $this->termColor->name);
    }
}
