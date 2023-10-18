<?php

namespace DTL\PhpTui\Model\Widget;

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

    public static function at(int $x, int $y): self
    {
        return new self($x, $y);
    }
}
