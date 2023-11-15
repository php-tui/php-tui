<?php

declare(strict_types=1);

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

final class MoveCursor implements Action
{
    public function __construct(public readonly int $line, public readonly int $col)
    {
    }

    public function __toString(): string
    {
        return sprintf('MoveCursor(line=%d,col=%d)', $this->line, $this->col);
    }
}
