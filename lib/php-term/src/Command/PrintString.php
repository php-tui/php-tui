<?php

namespace DTL\PhpTerm\Command;

use DTL\PhpTerm\TermCommand;

final class PrintString implements TermCommand
{
    public function __construct(private string $string)
    {
    }

    public function __toString(): string
    {
        return sprintf('Print("%s")', $this->string);
    }
}
