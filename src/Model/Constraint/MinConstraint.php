<?php

namespace DTL\PhpTui\Model\Constraint;

use DTL\PhpTui\Model\Constraint;

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
