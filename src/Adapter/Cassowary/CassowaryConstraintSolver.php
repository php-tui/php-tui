<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Areas;
use DTL\PhpTui\Model\ConstraintSolver;

final class CassowaryConstraintSolver implements ConstraintSolver
{
    public function solve(Area $target, array $constraints): Areas
    {
        return new Areas([$target]);
    }
}
