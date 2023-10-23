<?php

namespace PhpTui\Tui\Model\Constraint;

use PhpTui\Tui\Model\Constraint;

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
