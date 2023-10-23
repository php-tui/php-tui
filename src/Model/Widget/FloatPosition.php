<?php

namespace PhpTui\Tui\Model\Widget;

use PhpTui\Tui\Model\AxisBounds;
use Stringable;

final class FloatPosition implements Stringable
{
    public function __construct(public float $x, public float $y)
    {
    }

    public function __toString(): string
    {
        return sprintf('(%s,%s)', $this->x, $this->y);
    }

    public static function at(float $x, float $y): self
    {
        return new self($x, $y);
    }

    public function outOfBounds(AxisBounds $xBounds, AxisBounds $yBounds): bool
    {
        return (false === $xBounds->contains($this->x)) || (false === $yBounds->contains($this->y));
    }

    public function update(int $x, int $y): void
    {
        $this->x = $x;
        $this->y = $y;
    }
}
