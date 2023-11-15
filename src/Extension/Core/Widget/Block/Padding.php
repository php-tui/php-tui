<?php

namespace PhpTui\Tui\Extension\Core\Widget\Block;

class Padding
{
    private function __construct(public int $left, public int $right, public int $top, public int $bottom)
    {
    }

    public static function none(): self
    {
        return new self(0, 0, 0, 0);
    }

    public static function all(int $amount): self
    {
        return new self($amount, $amount, $amount, $amount);
    }

    public static function fromScalars(int $left, int $right, int $top, int $bottom): self
    {
        return new self($left, $right, $top, $bottom);
    }

}
