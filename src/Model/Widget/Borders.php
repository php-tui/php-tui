<?php

namespace PhpTui\Tui\Model\Widget;

final class Borders
{
    const NONE   = 0b0000;
    const TOP    = 0b0001;
    const RIGHT  = 0b0010;
    const BOTTOM = 0b0100;
    const LEFT   = 0b1000;
    const ALL    = self::TOP | self::RIGHT | self::BOTTOM | self::LEFT;

    public static function toString(int $borders): string
    {
        if ($borders === self::ALL) {
            return 'ALL';
        }

        if ($borders === self::NONE) {
            return 'NONE';
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
