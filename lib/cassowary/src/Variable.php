<?php

namespace DTL\Cassowary;

use Stringable;

class Variable implements Stringable
{
    public function __construct(public float $float)
    {
    }

    public function add(Variable $variable): Expression
    {
        return new Expression([
            new Term($this, 1.0),
            new Term($variable, 1.0),
        ], 0.0);

    }

    public function toExpression(): Expression
    {
        return new Expression([new Term($this, 1.0)], 0.0);
    }

    public function __toString(): string
    {
        return sprintf('%f', $this->float);
    }

}
