<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Areas;
use DTL\PhpTui\Model\ConstraintSolver;
use SplObjectStorage;

final class CassowaryConstraintSolver implements ConstraintSolver
{
    public function solve(Area $target, array $constraints): Areas
    {
        $vars = new SplObjectStorage();
        $elements = array_map(fn () => Element::empty(), $constraints, $constraints);
        $areas = array_map(fn () => Area::empty(), $constraints);

        return new Areas($areas);
    }
}
