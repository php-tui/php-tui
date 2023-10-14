<?php

namespace DTL\Cassowary\Tests;

use DTL\Cassowary\Constraint;
use DTL\Cassowary\Expression;
use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Term;
use DTL\Cassowary\Variable;
use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
{
    public function testGreaterThan(): void
    {
        $var1 = Variable::new(10.0);
        $c = Constraint::greaterThanOrEqualTo($var1, 10.0, Strength::WEAK);

        self::assertEquals(Strength::WEAK, $c->strength);
        self::assertEquals(RelationalOperator::GreaterThanOrEqualTo, $c->relationalOperator);
        self::assertEquals(new Expression(
            terms: [
                new Term($var1, 1.0),
            ],
            constant: -10.0
        ), $c->expression);
    }

    public function testLessThan(): void
    {
        $var1 = Variable::new(10.0);
        $c = Constraint::lessThanOrEqualTo($var1, 10.0, Strength::WEAK);

        self::assertEquals(Strength::WEAK, $c->strength);
        self::assertEquals(RelationalOperator::LessThanOrEqualTo, $c->relationalOperator);
        self::assertEquals(new Expression(
            terms: [
                new Term($var1, 1.0),
            ],
            constant: -10.0
        ), $c->expression);
    }

    public function testEqualTo(): void
    {
        $var1 = Variable::new(10.0);
        $c = Constraint::equalTo($var1, 10.0, Strength::WEAK);

        self::assertEquals(Strength::WEAK, $c->strength);
        self::assertEquals(RelationalOperator::Equal, $c->relationalOperator);
        self::assertEquals(new Expression(
            terms: [
                new Term($var1, 1.0),
            ],
            constant: -10.0
        ), $c->expression);
    }

    public function testRightHandSideIsAVariableOrExpression(): void
    {
        $var1 = Variable::new();
        $var2 = Variable::new();
        $var3 = Variable::new();

        $c = Constraint::equalTo($var1, $var2, Strength::WEAK);

        self::assertEquals(new Expression(
            terms: [
                new Term($var1, 1.0),
                new Term($var2, -1.0),
            ],
            constant: 0
        ), $c->expression);

        $c = Constraint::equalTo($var1, $var2->add($var3), Strength::WEAK);

        self::assertEquals(new Expression(
            terms: [
                new Term($var1, 1.0),
                new Term($var2, -1.0),
                new Term($var3, -1.0),
            ],
            constant: 0
        ), $c->expression);
    }
}
