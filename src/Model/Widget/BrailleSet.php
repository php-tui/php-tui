<?php

namespace PhpTui\Tui\Model\Widget;

final class BrailleSet
{
    // Braille patterns range from U+2800 to U+28FF
    public const RANGE = [0x2800, 0x28FF];
    public const BLANK = 0x2800;
    public const DOTS = [
        [0x0001, 0x0008],
        [0x0002, 0x0010],
        [0x0004, 0x0020],
        [0x0040, 0x0080],
    ];
}
