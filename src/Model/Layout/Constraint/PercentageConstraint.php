<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Layout\Constraint;

use PhpTui\Tui\Model\Layout\Constraint;

final class PercentageConstraint extends Constraint
{
    public function __construct(public int $percentage)
    {
    }

    public function __toString(): string
    {
        return sprintf('Percentage(%d)', $this->percentage);
    }

}
