<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Layout\Constraint;

use PhpTui\Tui\Model\Layout\Constraint;

final class MaxConstraint extends Constraint
{
    public function __construct(public int $max)
    {
    }

    public function __toString(): string
    {
        return sprintf('Max(%d)', $this->max);
    }

}
