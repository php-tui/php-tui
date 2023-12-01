<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Layout;

use PhpTui\Tui\Model\Display\Area;

use PhpTui\Tui\Model\Display\Areas;

interface ConstraintSolver
{
    /**
     * @param Constraint[] $constraints
     */
    public function solve(Layout $layout, Area $area, array $constraints): Areas;
}
