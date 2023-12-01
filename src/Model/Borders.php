<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

final class Borders
{
    public const NONE = 0b0000;
    public const TOP = 0b0001;
    public const RIGHT = 0b0010;
    public const BOTTOM = 0b0100;
    public const LEFT = 0b1000;
    public const VERTICAL = self::TOP | self::BOTTOM;
    public const HORIZONTAL = self::LEFT | self::RIGHT;
    public const ALL = self::TOP | self::RIGHT | self::BOTTOM | self::LEFT;

    public static function toString(int $borders): string
    {
        if ($borders === self::ALL) {
            return 'ALL';
        }

        if ($borders === self::NONE) {
            return 'NONE';
        }

        if ($borders === self::VERTICAL) {
            return 'VERTICAL';
        }

        if ($borders === self::HORIZONTAL) {
            return 'HORIZONTAL';
        }

        $string = [];
        if ($borders & self::TOP) {
            $string[] = 'TOP';
        }
        if ($borders & self::RIGHT) {
            $string[] = 'RIGHT';
        }
        if ($borders & self::BOTTOM) {
            $string[] = 'BOTTOM';
        }
        if ($borders & self::LEFT) {
            $string[] = 'LEFT';
        }

        return implode(',', $string);
    }
}
