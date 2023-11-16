<?php

declare(strict_types=1);

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

final class SetRgbForegroundColor implements Action
{
    public function __construct(public readonly int $r, public readonly int $g, public readonly int $b)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetRgbForegroundColor(%d, %d, %d)', $this->r, $this->g, $this->b);
    }
}
