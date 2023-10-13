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
}
