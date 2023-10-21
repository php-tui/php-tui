<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

class AlternateScreenEnable implements TermCommand
{
    public function __construct(public readonly bool $enable)
    {
    }

    public function __toString(): string
    {
        return sprintf('AlternateScreenEnable(%s)', $this->enable ? 'true':'false');
    }
}
