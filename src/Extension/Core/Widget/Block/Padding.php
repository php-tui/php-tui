<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Block;

final class Padding
{
    private function __construct(
        public readonly int $left,
        public readonly int $right,
        public readonly int $top,
        public readonly int $bottom
    ) {
    }

    public static function none(): self
    {
        return new self(0, 0, 0, 0);
    }

    public static function all(int $amount): self
    {
        return new self($amount, $amount, $amount, $amount);
    }

    public static function fromScalars(int $left = 0, int $right = 0, int $top = 0, int $bottom = 0): self
    {
        return new self($left, $right, $top, $bottom);
    }

    public static function vertical(int $amount): self
    {
        return new self(0, 0, $amount, $amount);
    }

    public static function horizontal(int $amount): self
    {
        return new self($amount, $amount, 0, 0);
    }

    public static function left(int $left): self
    {
        return new self($left, 0, 0, 0);
    }

    public static function right(int $right): self
    {
        return new self(0, $right, 0, 0);
    }

    public static function top(int $top): self
    {
        return new self(0, 0, $top, 0);
    }

    public static function bottom(int $bottom): self
    {
        return new self(0, 0, 0, $bottom);
    }
}
