<?php

declare(strict_types=1);

namespace PhpTui\Tui\Symbol;

final class BarSet
{
    public const EMPTY = ' ';
    public const FULL = '█';
    public const SEVEN_EIGHTHS = '▇';
    public const THREE_QUARTERS = '▆';
    public const FIVE_EIGHTHS = '▅';
    public const HALF = '▄';
    public const THREE_EIGHTHS = '▃';
    public const ONE_QUARTER = '▂';
    public const ONE_EIGHTH = '▁';

    public static function fromIndex(int $index): string
    {
        return match ($index) {
            0 => self::EMPTY,
            1 => self::ONE_EIGHTH,
            2 => self::ONE_QUARTER,
            3 => self::THREE_EIGHTHS,
            4 => self::HALF,
            5 => self::FIVE_EIGHTHS,
            6 => self::THREE_QUARTERS,
            7 => self::SEVEN_EIGHTHS,
            default => self::FULL,
        };
    }
}
