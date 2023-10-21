<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;

final class SetForegroundColor implements TermCommand
{
    public function __construct(public readonly TermColor $color)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetForegroundColor(%s)', $this->color->name);
    }
}
