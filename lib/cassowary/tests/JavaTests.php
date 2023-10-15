<?php

namespace DTL\Cassowary\Tests;

use DTL\Cassowary\Constraint;
use DTL\Cassowary\Solver;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Variable;
use PHPUnit\Framework\TestCase;

class JavaTests extends TestCase
{
    private const EPSILON = 1.0E-8;

    public function testVariableAdd(): void
    {
        $solver = Solver::new();
        $x = Variable::new();

        $solver->addConstraint(Constraint::equalTo($x->add(2.0), 20, Strength::REQUIRED));

        self::assertEqualsWithDelta(18, $solver->fetchChanges()->getValue($x), self::EPSILON);
    }

     public function testCompareTwoVariableAdds(): void {
         $solver = Solver::new();
         $x = Variable::new();
         $y = Variable::new();
    
         $solver->addConstraints([
             Constraint::equalTo($x, 20, Strength::REQUIRED),
             Constraint::equalTo($x->add(2.0), $y->add(10.0), Strength::REQUIRED),
         ]);
    
         $changes = $solver->fetchChanges();
    
         self::assertEqualsWithDelta(20, $changes->getValue($x), self::EPSILON);
         self::assertEqualsWithDelta(12, $changes->getValue($y), self::EPSILON);
     }
    //
    //    @Test
    //    public function testSimple1(): void {
    //        Variable x = new Variable("x");
    //        Variable y = new Variable("y");
    //        Solver solver = new Solver();
    //        solver.addConstraint(Symbolics.equals(x, y));
    //        $solver->updateVariables();
    //        self::assertEquals(x.getValue(), y.getValue(), EPSILON);
    //    }
    //
    //    @Test
    //    public function testCasso1(): void {
    //        Variable x = new Variable("x");
    //        Variable y = new Variable("y");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.lessThanOrEqualTo(x, y));
    //        solver.addConstraint(Symbolics.equals(y, Symbolics.add(x, 3.0)));
    //        solver.addConstraint(Symbolics.equals(x, 10.0).setStrength(Strength.WEAK));
    //        solver.addConstraint(Symbolics.equals(y, 10.0).setStrength(Strength.WEAK));
    //
    //        $solver->updateVariables();
    //
    //        if (Math.abs(x.getValue() - 10.0) < EPSILON) {
    //            self::assertEquals(10, x.getValue(), EPSILON);
    //            self::assertEquals(13, y.getValue(), EPSILON);
    //        } else {
    //            self::assertEquals(7, x.getValue(), EPSILON);
    //            self::assertEquals(10, y.getValue(), EPSILON);
    //        }
    //    }
    //
    //    @Test
    //    public function testAddDelete1(): void {
    //        Variable x = new Variable("x");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.lessThanOrEqualTo(x, 100).setStrength(Strength.WEAK));
    //
    //        $solver->updateVariables();
    //        self::assertEquals(100, x.getValue(), EPSILON);
    //
    //        Constraint c10 = Symbolics.lessThanOrEqualTo(x, 10.0);
    //        Constraint c20 = Symbolics.lessThanOrEqualTo(x, 20.0);
    //
    //        solver.addConstraint(c10);
    //        solver.addConstraint(c20);
    //
    //        $solver->updateVariables();
    //
    //        self::assertEquals(10, x.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c10);
    //
    //        $solver->updateVariables();
    //
    //        self::assertEquals(20, x.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c20);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(100, x.getValue(), EPSILON);
    //
    //        Constraint c10again = Symbolics.lessThanOrEqualTo(x, 10.0);
    //
    //        solver.addConstraint(c10again);
    //        solver.addConstraint(c10);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(10, x.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c10);
    //        $solver->updateVariables();
    //        self::assertEquals(10, x.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c10again);
    //        $solver->updateVariables();
    //        self::assertEquals(100, x.getValue(), EPSILON);
    //    }
    //
    //    @Test
    //    public function testAddDelete2(): void {
    //        Variable x = new Variable("x");
    //        Variable y = new Variable("y");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.equals(x, 100).setStrength(Strength.WEAK));
    //        solver.addConstraint(Symbolics.equals(y, 120).setStrength(Strength.STRONG));
    //
    //        Constraint c10 = Symbolics.lessThanOrEqualTo(x, 10.0);
    //        Constraint c20 = Symbolics.lessThanOrEqualTo(x, 20.0);
    //
    //        solver.addConstraint(c10);
    //        solver.addConstraint(c20);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(10, x.getValue(), EPSILON);
    //        self::assertEquals(120, y.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c10);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(20, x.getValue(), EPSILON);
    //        self::assertEquals(120, y.getValue(), EPSILON);
    //
    //        Constraint cxy = Symbolics.equals(Symbolics.multiply(x, 2.0), y);
    //        solver.addConstraint(cxy);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(20, x.getValue(), EPSILON);
    //        self::assertEquals(40, y.getValue(), EPSILON);
    //
    //        solver.removeConstraint(c20);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(60, x.getValue(), EPSILON);
    //        self::assertEquals(120, y.getValue(), EPSILON);
    //
    //        solver.removeConstraint(cxy);
    //        $solver->updateVariables();
    //
    //        self::assertEquals(100, x.getValue(), EPSILON);
    //        self::assertEquals(120, y.getValue(), EPSILON);
    //    }
    //
    //    @Test(expected = UnsatisfiableConstraintException.class)
    //    public function testInconsistent1(): void {
    //        Variable x = new Variable("x");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.equals(x, 10.0));
    //        solver.addConstraint(Symbolics.equals(x, 5.0));
    //
    //        $solver->updateVariables();
    //    }
    //
    //    @Test(expected = UnsatisfiableConstraintException.class)
    //    public function testInconsistent2(): void {
    //        Variable x = new Variable("x");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(x, 10.0));
    //        solver.addConstraint(Symbolics.lessThanOrEqualTo(x, 5.0));
    //        $solver->updateVariables();
    //    }
    //
    //    @Test(expected = UnsatisfiableConstraintException.class)
    //    public function testInconsistent3(): void {
    //
    //        Variable w = new Variable("w");
    //        Variable x = new Variable("x");
    //        Variable y = new Variable("y");
    //        Variable z = new Variable("z");
    //        Solver solver = new Solver();
    //
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(w, 10.0));
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(x, w));
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(y, x));
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(z, y));
    //        solver.addConstraint(Symbolics.greaterThanOrEqualTo(z, 8.0));
    //        solver.addConstraint(Symbolics.lessThanOrEqualTo(z, 4.0));
    //        $solver->updateVariables();
    //    }
    //
}
