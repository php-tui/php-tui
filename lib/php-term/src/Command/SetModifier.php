<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\TermModifier;

final class SetModifier implements TermCommand
{
    public function __construct(public readonly TermModifier $modifier, public bool $enable)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetModifier(%s,%s)', $this->modifier->name, $this->enable ? 'on' : 'off');
    }
}
