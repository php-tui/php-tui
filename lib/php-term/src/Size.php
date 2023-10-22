<?php

namespace DTL\PhpTerm;

final class Size implements TerminalInformation
{
    public function __construct(public int $lines, public int $cols)
    {
    }
}
