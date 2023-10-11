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
            '{operator: %s, expression: %s, strength: %s}',
            $this->relationalOperator->name,
            $this->expression->__toString(),
            $this->strength
        );
    }

}
