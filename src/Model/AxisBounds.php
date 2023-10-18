<?php

namespace DTL\PhpTui\Model;

final class AxisBounds
{
    public function __construct(public int $min, public int $max)
    {
    }

    public function contains(float $value): bool
    {
        return $value >= $this->min && $value <= $this->max;
    }

    public function length(): int
    {
        return abs($this->max - $this->min);
    }
}
