<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\Cassowary\Constraint;
use DTL\Cassowary\Expression;
use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Solver;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Term;
use DTL\Cassowary\Variable;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Areas;
use DTL\PhpTui\Model\ConstraintSolver;
use DTL\PhpTui\Model\Constraint\LengthConstraint;
use DTL\PhpTui\Model\Constraint\MaxConstraint;
use DTL\PhpTui\Model\Constraint\MinConstraint;
use DTL\PhpTui\Model\Constraint\PercentageConstraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use RuntimeException;
use SplObjectStorage;

final class CassowaryConstraintSolver implements ConstraintSolver
{
    public function solve(Layout $layout, Area $area, array $constraints): Areas
    {
        $solver = Solver::new();
        $inner = $area->inner($layout->margin);

        [$areaStart, $areaEnd] = match ($layout->direction) {
            Direction::Horizontal => [$inner->x, $inner->right()],
            Direction::Vertical => [$inner->y, $inner->bottom()],
        };

        $areaSize = $areaEnd - $areaStart;

        $elements = array_map(fn () => new Element(), $layout->constraints);

        // ensure that all the elements are inside the area
        foreach ($elements as $element) {
            $solver->addConstraints([
                Constraint::greaterThanOrEqualTo($element->start, $areaStart, Strength::REQUIRED),
                Constraint::lessThanOrEqualTo($element->end, $areaEnd, Strength::REQUIRED),
                Constraint::lessThanOrEqualTo($element->start, $element->end, Strength::REQUIRED),
            ]);
        }

        // ensure there are no gaps between the elements
        $previousElement = null;
        foreach ($elements as $element) {
            if ($previousElement === null) {
                $previousElement = $element;
                continue;
            }
            $solver->addConstraint(
                Constraint::equalTo($previousElement->end, $element->start, Strength::REQUIRED)
            );
        }

        return new Areas($areas);
    }
}
