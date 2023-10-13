<?php

namespace DTL\Cassowary;

use RuntimeException;
use Stringable;

class Variable implements Stringable
{
    public function __construct(public float $float, public ?string $label = null)
    {
    }

    public function add(mixed $value): Expression
    {
        if ($value instanceof Variable) {
            return new Expression([
                new Term($this, 1.0),
                new Term($value, 1.0),
            ], 0.0);
        }

        if (is_float($value)) {
            return new Expression([
                new Term($this, 1.0),
            ], $value);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to add %s to a Variable',
            is_object($value) ? $value::class : gettype($value)
        ));
    }

    public function toExpression(): Expression
    {
        return new Expression([new Term($this, 1.0)], 0.0);
    }

    public function __toString(): string
    {
        return sprintf('%f', $this->float);
    }

    public static function new(float $value = 0.0): self
    {
        return new self($value);
    }
}
