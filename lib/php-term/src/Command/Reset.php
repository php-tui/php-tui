<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

class Reset implements TermCommand
{
    public function __toString(): string
    {
        return 'Reset()';
    }
}
