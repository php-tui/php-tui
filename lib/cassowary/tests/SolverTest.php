<?php

namespace DTL\Cassowary\Tests;

use DTL\Cassowary\AddConstraintaintError;
use DTL\Cassowary\Constraint;
use DTL\Cassowary\Expression;
use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Solver;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Variable;
use PHPUnit\Framework\TestCase;

class SolverTest extends TestCase
{
    public function testDuplicateConstraint(): void
    {
        $this->expectException(AddConstraintaintError::class);
        $constraint = new Constraint(
            relationalOperator: RelationalOperator::Equal,
            expression: (Variable::new())->toExpression(),
            strength: Strength::REQUIRED 
        );
        Solver::new()->addConstraints([
            $constraint,
            $constraint
        ]);
    }

    public function testTuiExample1(): void
    {
        $s = Solver::new();
        $v0 = Variable::new();
        $v1 = Variable::new();
        $v2 = Variable::new();
        $v3 = Variable::new();
        $v4 = Variable::new();
        $v5 = Variable::new();
        $v6 = Variable::new();
        $s->addConstraints([
            Constraint::greaterThanOrEqualTo($v0, 0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v1, 33.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v0, $v1, Strength::REQUIRED),
            Constraint::greaterThanOrEqualTo($v2, 0, Strength::REQUIRED),

            Constraint::lessThanOrEqualTo($v3, 33.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v2, $v3, Strength::REQUIRED),
            Constraint::greaterThanOrEqualTo($v4, 0.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v5, 33.0, Strength::REQUIRED),

            Constraint::lessThanOrEqualTo($v4, $v5, Strength::REQUIRED),
            Constraint::equalTo($v1, $v2, Strength::REQUIRED),
            Constraint::equalTo($v1, $v4, Strength::REQUIRED),
            Constraint::equalTo($v0, 0.0, Strength::REQUIRED),

            Constraint::equalTo($v5, 33.0, Strength::REQUIRED),
            Constraint::equalTo($v1->sub($v0), 3.3, Strength::STRONG),
            Constraint::lessThanOrEqualTo($v3->sub($v2), 5.0, Strength::STRONG),
            Constraint::equalTo($v3->sub($v2), 5.0, Strength::MEDIUM),

            Constraint::greaterThanOrEqualTo($v5->sub($v4), 5.0, Strength::MEDIUM),
            Constraint::equalTo($v5->sub($v4), 1.0, Strength::MEDIUM),
        ]);
        $changes = $s->fetchChanges();
        self::assertEquals(
            [
                3.3,
                33.0,
                3.3,
                8.3,
            ],
            [
                $changes->getValue($v4),
                $changes->getValue($v5),
                $changes->getValue($v1),
                $changes->getValue($v3),
            ],
        );
    }

    public function testTuiExample2(): void
    {
        $s = Solver::new();
        $v0 = Variable::new();
        $v1 = Variable::new();
        $v2 = Variable::new();
        $v3 = Variable::new();
        $v4 = Variable::new();
        $v5 = Variable::new();
        $v6 = Variable::new();
        $s->addConstraints([
            Constraint::greaterThanOrEqualTo($v0, 0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v1, 100.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v0, $v1, Strength::REQUIRED),
            Constraint::greaterThanOrEqualTo($v2, 0, Strength::REQUIRED),

            Constraint::lessThanOrEqualTo($v3, 100.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v2, $v3, Strength::REQUIRED),
            Constraint::greaterThanOrEqualTo($v4, 0.0, Strength::REQUIRED),
            Constraint::lessThanOrEqualTo($v5, 100.0, Strength::REQUIRED),

            Constraint::lessThanOrEqualTo($v4, $v5, Strength::REQUIRED),
            Constraint::equalTo($v1, $v2, Strength::REQUIRED),
            Constraint::equalTo($v1, $v4, Strength::REQUIRED),
            Constraint::equalTo($v0, 0.0, Strength::REQUIRED),

            Constraint::equalTo($v5, 100.0, Strength::REQUIRED),
            Constraint::equalTo($v1->sub($v0), 50.0, Strength::STRONG),
            Constraint::lessThanOrEqualTo($v3->sub($v2), 25.0, Strength::STRONG),
            Constraint::equalTo($v3->sub($v2), 25.0, Strength::MEDIUM),
            Constraint::equalTo($v5->sub($v4), 25.0, Strength::MEDIUM),
        ]);
        $changes = $s->fetchChanges();
        self::assertEquals(
            [
                50.0,
                100.0,
                50.0,
                75.0,
                50.0
            ],
            [
                $changes->getValue($v1),
                $changes->getValue($v5),
                $changes->getValue($v4),
                $changes->getValue($v3),
                $changes->getValue($v2),
            ],
        );
    }
}