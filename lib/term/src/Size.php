<?php

declare(strict_types=1);

namespace PhpTui\Term;

use Stringable;

final class Size implements TerminalInformation, Stringable
{
    public function __construct(public int $lines, public int $cols)
    {
    }

    public function __toString(): string
    {
        return sprintf('%dx%d', $this->cols, $this->lines);
    }
}
