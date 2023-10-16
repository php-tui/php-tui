<?php

namespace DTL\Cassowary\Tests;

use DTL\Cassowary\Constraint;
use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Solver;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Variable;
use DTL\PhpTui\Model\Exception\TodoException;
use PHPUnit\Framework\TestCase;
use SplObjectStorage;

class QuadrilateralTest extends TestCase
{
    public function testQuadrilateral(): void
    {
        $points = [
            Point::new(),
            Point::new(),
            Point::new(),
            Point::new(),
        ];
        $points[0]->x->label = '0.x';
        $pointStarts = [
            [10.0, 10.0],
            [10.0, 200.0],
            [200.0, 200.0],
            [200.0, 10.0],
        ];
        $midPoints = [
            Point::new(),
            Point::new(),
            Point::new(),
            Point::new(),
        ];
        $solver = Solver::new();
        $weight = 1.0;
        $multiplier = 2.0;

        foreach (range(0, 3) as $i) {
            $solver->addConstraints([
                Constraint::equalTo($points[$i]->x, $pointStarts[$i][0], Strength::WEAK),
                Constraint::equalTo($points[$i]->y, $pointStarts[$i][1], Strength::WEAK),
            ]);
            $weight *= $multiplier;
        }

        foreach ([[0,1], [1,2], [2,3], [3,0]] as [$start, $end]) {
            $solver->addConstraints([
                Constraint::equalTo($midPoints[$start]->x, $points[$start]->x->add($points[$end]->x)->div(2), Strength::REQUIRED),
                Constraint::equalTo($midPoints[$start]->y, $points[$start]->y->add($points[$end]->y)->div(2), Strength::REQUIRED),
            ]);
        }
        $solver->addConstraints([
            Constraint::lessThanOrEqualTo($points[0]->x->add(20.0), $points[2]->x, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[0]->x->add(20.0), $points[3]->x, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[1]->x->add(20.0), $points[2]->x, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[1]->x->add(20.0), $points[3]->x, Strength::STRONG),

            Constraint::lessThanOrEqualTo($points[0]->y->add(20.0), $points[1]->y, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[0]->y->add(20.0), $points[2]->y, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[3]->y->add(20.0), $points[1]->y, Strength::STRONG),
            Constraint::lessThanOrEqualTo($points[3]->y->add(20.0), $points[2]->y, Strength::STRONG),
        ]);


        foreach ($points as $point) {
            $solver->addConstraints([
                Constraint::greaterThanOrEqualTo($point->x, 0.0, Strength::REQUIRED),
                Constraint::greaterThanOrEqualTo($point->y, 0.0, Strength::REQUIRED),
                Constraint::lessThanOrEqualTo($point->x, 500.0, Strength::REQUIRED),
                Constraint::lessThanOrEqualTo($point->y, 500.0, Strength::REQUIRED),
            ]);
        }

        $changes = $solver->fetchChanges();

        self::assertEquals(
            [10.0, 105],
            $changes->getValues($midPoints[0]->x, $midPoints[0]->y),
        );
        self::assertEquals(
            [105.0, 200],
            $changes->getValues($midPoints[1]->x, $midPoints[1]->y),
        );
        self::assertEquals(
            [200.0, 105],
            $changes->getValues($midPoints[2]->x, $midPoints[2]->y),
        );
        self::assertEquals(
            [105.0, 10.0],
            $changes->getValues($midPoints[3]->x, $midPoints[3]->y),
        );
    }
}

class Point {
    public function __construct(public Variable $x, public Variable $y)
    {
    }

    public static function new(): self
    {
        return new self(Variable::new(), Variable::new());
    }
}
