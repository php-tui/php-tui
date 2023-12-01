<?php

declare(strict_types=1);

namespace PhpTui\Tui\Layout;

use PhpTui\Tui\Display\Area;

use PhpTui\Tui\Display\Areas;

interface ConstraintSolver
{
    /**
     * @param Constraint[] $constraints
     */
    public function solve(Layout $layout, Area $area, array $constraints): Areas;
}
