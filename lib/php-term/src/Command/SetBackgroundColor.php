<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;

final class SetBackgroundColor implements TermCommand
{
    public function __construct(private TermColor $termColor)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetBackgroundColor(%s)', $this->termColor->name);
    }
}
