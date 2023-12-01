<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Chart;

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

    public static function default(): self
    {
        return new self(0.0, 0.0);
    }

    public static function new(float $min, float $max): self
    {
        return new self($min, $max);
    }
}
