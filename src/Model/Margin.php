<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

final class Margin
{
    public function __construct(
        /**
         * @var int<0,max>
         */
        public readonly int $vertical,
        /**
         * @var int<0,max>
         */
        public readonly int $horizontal
    ) {
    }

    public static function none(): self
    {
        return new self(0, 0);
    }

    /**
     * @param int<0,max> $amount
     */
    public static function all(int $amount): self
    {
        return new self($amount, $amount);
    }

    /**
     * @param int<0,max> $vertical
     * @param int<0,max> $horizontal
     */
    public static function fromScalars(int $vertical, int $horizontal): self
    {
        return new self($vertical, $horizontal);
    }

    /**
     * @param int<0,max> $vertical
     */
    public static function vertical(int $vertical): self
    {
        return new self($vertical, 0);
    }

    /**
     * @param int<0,max> $horizontal
     */
    public static function horizontal(int $horizontal): self
    {
        return new self(0, $horizontal);
    }
}
