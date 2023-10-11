<?php

namespace DTL\PhpTui\Model;

interface ConstraintSolver
{
    /**
     * @param Constraint[] $constraints
     */
    public function solve(Area $target, array $constraints): Areas;
}
