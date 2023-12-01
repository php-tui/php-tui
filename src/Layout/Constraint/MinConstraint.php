<?php

declare(strict_types=1);

namespace PhpTui\Tui\Layout\Constraint;

use PhpTui\Tui\Layout\Constraint;

final class MinConstraint extends Constraint
{
    public function __construct(public int $min)
    {
    }

    public function __toString(): string
    {
        return sprintf('Min(%d)', $this->min);
    }

}
