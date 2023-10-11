<?php

namespace DTL\PhpTui\Model;

interface ConstraintSolver
{
    /**
     * @param Constraint[] $constraints
     */
    public function solve(Layout $layout, Area $area, array $constraints): Areas;
}
