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
                new Constraint(
                    RelationalOperator::Equal,
                    $points[$i]->x->toExpression()->assign($pointStarts[$i][0]),
                    Strength::WEAK * $weight
                ),
                new Constraint(
                    RelationalOperator::Equal,
                    $points[$i]->y->toExpression()->assign($pointStarts[$i][1]),
                    Strength::WEAK * $weight
                )
            ]);
            $weight *= $multiplier;
        }

        foreach ([[0,1], [1,2], [2,3], [3,0]] as [$start, $end]) {
            $solver->addConstraints([
                new Constraint(
                    RelationalOperator::Equal,
                    $midPoints[$start]->x->toExpression()->add($points[$start]->x->add($points[$end]->x)->div(2)),
                    Strength::REQUIRED
                ),
                new Constraint(
                    RelationalOperator::Equal,
                    $midPoints[$start]->y->toExpression()->add($points[$start]->y->add($points[$end]->y)->div(2)),
                    Strength::REQUIRED
                )
            ]);
        }
        $solver->addConstraints([
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[0]->x->add(20.0)->add($points[2]->x),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[0]->x->add(20.0)->add($points[3]->x),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[1]->x->add(20.0)->add($points[2]->x),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[1]->x->add(20.0)->add($points[3]->x),
                Strength::STRONG,
            ),

            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[0]->y->add(20.0)->add($points[1]->y),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[0]->y->add(20.0)->add($points[2]->y),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[3]->y->add(20.0)->add($points[1]->y),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $points[3]->y->add(20.0)->add($points[2]->y),
                Strength::STRONG,
            ),
        ]);


        foreach ($points as $point) {
            $solver->addConstraints([
                new Constraint(
                    RelationalOperator::GreaterThanOrEqualTo,
                    $point->x->toExpression()->assign(0.0),
                    Strength::REQUIRED,
                ),
                new Constraint(
                    RelationalOperator::GreaterThanOrEqualTo,
                    $point->y->toExpression()->assign(0.0),
                    Strength::REQUIRED,
                ),
                new Constraint(
                    RelationalOperator::LessThanOrEqualTo,
                    $point->x->toExpression()->assign(500.0),
                    Strength::REQUIRED,
                ),
                new Constraint(
                    RelationalOperator::LessThanOrEqualTo,
                    $point->y->toExpression()->assign(500.0),
                    Strength::REQUIRED,
                ),
            ]);
        }

        $changes = $solver->fetchChanges();

        dump($changes);
        dd($midPoints);
        throw new TodoException('TODO: Assert changes');
    }
}

class Point {
    public function __construct(public Variable $x, public Variable $y)
    {
    }

    public static function new(): self
    {
        return new self(new Variable(0.0), new Variable(0.0));
    }
}
