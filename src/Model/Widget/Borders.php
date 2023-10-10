<?php

namespace DTL\PhpTui\Model\Widget;

final class Borders
{
    const NONE   = 0b0000;
    const TOP    = 0b0001;
    const RIGHT  = 0b0010;
    const BOTTOM = 0b0100;
    const LEFT   = 0b1000;
    const ALL    = self::TOP | self::RIGHT | self::BOTTOM | self::LEFT;
}
