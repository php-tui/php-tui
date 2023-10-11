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
        $this->expectExceptionMessage(
            'Constraint {operator: Equal, expression: (10.000000 * 1.000000) constant: 0.000000, strength: 1001001000}'
        );
        $constraint = new Constraint(
            relationalOperator: RelationalOperator::Equal,
            expression: (new Variable(10))->toExpression(),
            strength: Strength::REQUIRED 
        );
        Solver::new()->addConstraints([
            $constraint,
            $constraint
        ]);
    }
}
