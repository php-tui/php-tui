<?php

namespace DTL\PhpTui\Model;

enum Modifier: int
{
    case None        = 0b000000000000;
    case Bold        = 0b000000000001;
    case Dim         = 0b000000000010;
    case Italic      = 0b000000000100;
    case Underlined  = 0b000000001000;
    case SlowBlink   = 0b000000010000;
    case RapidBlink  = 0b000000100000;
    case Reversed    = 0b000001000000;
    case Hidden      = 0b000010000000;
    case CrossedOut  = 0b000100000000;

    public function add(self $modifier): int
    {
        return $this->value | $modifier->value;
    }
}
