<?php

namespace DTL\Cassowary;

use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Strength;
use Stringable;

final class Constraint implements Stringable
{
    public function __construct(
        public RelationalOperator $relationalOperator,
        public Expression $expression,
        public float $strength
    )
    {
    }

    public function __toString(): string
    {
        return sprintf(
            '{operator: %s, lhsession: %s, strength: %s}',
            $this->relationalOperator->name,
            $this->expression->__toString(),
            $this->strength
        );
    }

    public static function new(RelationalOperator $operator, Variable|Expression $expr, Variable|Expression|float $rhs, float$strength): self {
        if ($expr instanceof Variable) {
            $expr = $expr->toExpression();
        }
        if ($rhs instanceof Variable) {
            $rhs = $rhs->toExpression();
        }
        if (is_float($rhs)) {
            $expr->constant($rhs);
        } else {
            $expr = $expr->add($rhs->negate());
        }
        return new self($operator, $expr, $strength);
    }

    public static function greaterThanOrEqualTo(Variable|Expression $lhs, Variable|Expression|float $rhs, float $strength): Constraint
    {
        return self::new(RelationalOperator::GreaterThanOrEqualTo, $lhs, $rhs, $strength);
    }

    public static function lessThanOrEqualTo(Variable|Expression $lhs, Variable|Expression|float $rhs, float $strength): Constraint
    {
        return self::new(RelationalOperator::LessThanOrEqualTo, $lhs, $rhs, $strength);
    }

    public static function equalTo(Variable|Expression $lhs, Variable|Expression|float $rhs, float $strength): Constraint
    {
        return self::new(RelationalOperator::Equal, $lhs, $rhs, $strength);
    }

}
