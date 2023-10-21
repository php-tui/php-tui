<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\TermModifier;

final class SetModifier implements TermCommand
{
    public function __construct(private TermModifier $modifier)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetModifier(%s)', $this->modifier->name);
    }
}
