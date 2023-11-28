<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

final class Margin
{
    public function __construct(
        public readonly int $vertical,
        public readonly int $horizontal
    ) {
    }

    public static function none(): self
    {
        return new self(0, 0);
    }

    public static function all(int $amount): self
    {
        return new self($amount, $amount);
    }

    public static function fromScalars(int $vertical, int $horizontal): self
    {
        return new self($vertical, $horizontal);
    }

    public static function vertical(int $vertical): self
    {
        return new self($vertical, 0);
    }

    public static function horizontal(int $horizontal): self
    {
        return new self(0, $horizontal);
    }
}
