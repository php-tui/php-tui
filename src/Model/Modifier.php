<?php

namespace PhpTui\Tui\Model;

final class Modifier
{
    public const NONE        = 0b000000000000;
    public const BOLD        = 0b000000000001;
    public const DIM         = 0b000000000010;
    public const ITALIC      = 0b000000000100;
    public const UNDERLINED  = 0b000000001000;
    public const SLOWBLINK   = 0b000000010000;
    public const RAPIDBLINK  = 0b000000100000;
    public const REVERSED    = 0b000001000000;
    public const HIDDEN      = 0b000010000000;
    public const CROSSEDOUT  = 0b000100000000;
}
