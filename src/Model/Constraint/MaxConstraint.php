<?php

namespace DTL\PhpTui\Model\Constraint;

use DTL\PhpTui\Model\Constraint;

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
