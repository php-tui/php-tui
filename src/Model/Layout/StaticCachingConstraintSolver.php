<?php

namespace PhpTui\Tui\Model\Layout;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Areas;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\ConstraintSolver;
use PhpTui\Tui\Model\Layout;

class StaticCachingConstraintSolver implements ConstraintSolver
{
    /**
     * @var array<string,Areas>
     */
    private static array $cache;

    private ConstraintSolver $inner;

    public function __construct(ConstraintSolver $inner)
    {
        $this->inner = $inner;
    }

    public function solve(Layout $layout, Area $area, array $constraints): Areas
    {
        $hash = $area->__toString().implode('', array_reduce($constraints, function (array $ac, Constraint $c) {
            $ac[] = $c->__toString();
            return $ac;
        }, []));

        if (isset(self::$cache[$hash])) {
            return self::$cache[$hash];
        }

        self::$cache[$hash] = $this->inner->solve($layout, $area, $constraints);
        return self::$cache[$hash];
    }
}
