<?php

namespace DTL\PhpTui\Model;

final class AxisBounds
{
    public function __construct(public float $min, public float $max)
    {
    }

    public function contains(float $value): bool
    {
        return $value >= $this->min && $value <= $this->max;
    }

    public function length(): float
    {
        return abs($this->max - $this->min);
    }
}
