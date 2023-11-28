<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Block;

final class Padding
{
    private function __construct(
        /**
         * @var int<0,max>
         */
        public readonly int $left,
        /**
         * @var int<0,max>
         */
        public readonly int $right,
        /**
         * @var int<0,max>
         */
        public readonly int $top,
        /**
         * @var int<0,max>
         */
        public readonly int $bottom
    ) {
    }

    public static function none(): self
    {
        return new self(0, 0, 0, 0);
    }

    /**
     * @param int<0,max> $amount
     */
    public static function all(int $amount): self
    {
        return new self($amount, $amount, $amount, $amount);
    }

    /**
     * @param int<0,max> $left
     * @param int<0,max> $right
     * @param int<0,max> $bottom
     * @param int<0,max> $top
     */
    public static function fromScalars(int $left = 0, int $right = 0, int $top = 0, int $bottom = 0): self
    {
        return new self($left, $right, $top, $bottom);
    }

    /**
     * @param int<0,max> $amount
     */
    public static function vertical(int $amount): self
    {
        return new self(0, 0, $amount, $amount);
    }

    /**
     * @param int<0,max> $amount
     */
    public static function horizontal(int $amount): self
    {
        return new self($amount, $amount, 0, 0);
    }

    /**
     * @param int<0,max> $left
     */
    public static function left(int $left): self
    {
        return new self($left, 0, 0, 0);
    }

    /**
     * @param int<0,max> $right
     */
    public static function right(int $right): self
    {
        return new self(0, $right, 0, 0);
    }

    /**
     * @param int<0,max> $top
     */
    public static function top(int $top): self
    {
        return new self(0, 0, $top, 0);
    }

    /**
     * @param int<0,max> $bottom
     */
    public static function bottom(int $bottom): self
    {
        return new self(0, 0, 0, $bottom);
    }
}
