<?php

namespace DTL\PhpTui\Model\Widget;

final class BrailleSet
{
    public const BLANK = 0x2800;
    public const DOTS = [
        [0x0001, 0x0008],
        [0x0002, 0x0010],
        [0x0004, 0x0020],
        [0x0040, 0x0080],
    ];
}
