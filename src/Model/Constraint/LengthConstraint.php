<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Constraint;

use PhpTui\Tui\Model\Layout\Constraint;

class LengthConstraint extends Constraint
{
    public function __construct(public int $length)
    {
    }

    public function __toString(): string
    {
        return sprintf('Length(%d)', $this->length);
    }
}
