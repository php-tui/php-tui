<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;

final class SetBackgroundColor implements TermCommand
{
    public function __construct(public readonly TermColor $color)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetBackgroundColor(%s)', $this->color->name);
    }
}
