<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Block;

final class Padding
{
    private function __construct(
        public int $left,
        public int $right,
        public int $top,
        public int $bottom
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

    public static function fromScalars(int $left, int $right, int $top, int $bottom): self
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

    public function left(int $left): self
    {
        $this->left = $left;

        return $this;
    }

    public function right(int $right): self
    {
        $this->right = $right;

        return $this;
    }

    public function top(int $top): self
    {
        $this->top = $top;

        return $this;
    }

    public function bottom(int $bottom): self
    {
        $this->bottom = $bottom;

        return $this;
    }
}
