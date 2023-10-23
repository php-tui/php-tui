<?php

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

final class SetRgbBackgroundColor implements Action
{
    public function __construct(public readonly int $r, public readonly int $g, public readonly int $b)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetRgbBackgroundColor(%d, %d, %d)', $this->r, $this->g, $this->b);
    }
}
