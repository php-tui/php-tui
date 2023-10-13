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

    public function testAddConstraint(): void
    {
        $variable = Variable::new();
        $variable2 = Variable::new();
        $variable3 = Variable::new();
        $solver = Solver::new();
        $solver->addConstraints([
            new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                $variable->toExpression()->constant(1),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $variable->toExpression()->constant(8),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                $variable2->toExpression()->add($variable),
                Strength::STRONG,
            ),
            new Constraint(
                RelationalOperator::Equal,
                $variable3->toExpression()->constant(1),
                Strength::REQUIRED,
            ),
        ]);
        dump($solver->fetchChanges());
    }
}
