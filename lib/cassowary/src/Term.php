<?php

namespace DTL\Cassowary;

use Stringable;

class Term implements Stringable
{
    public function __construct(public Variable $variable, public float $coefficient = 1.0)
    {
    }

    public function __toString(): string
    {
        return sprintf('(%s * %f)', $this->variable->__toString(), $this->coefficient);
    }

    public function div(float $divisor): self
    {
        return new self($this->variable, $this->coefficient / $divisor);
    }

}
