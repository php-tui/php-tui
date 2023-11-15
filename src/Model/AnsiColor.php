<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

enum AnsiColor: int implements Color
{
    case Reset = -1;
    case Black = 0;
    case Red = 1;
    case Green = 2;
    case Yellow = 3;
    case Blue = 4;
    case Magenta = 5;
    case Cyan = 6;
    case Gray = 7;
    case DarkGray = 8;
    case LightRed = 9;
    case LightGreen = 10;
    case LightYellow = 11;
    case LightBlue = 12;
    case LightMagenta = 13;
    case LightCyan = 14;
    case White = 15;

    public function debugName(): string
    {
        return $this->name;
    }
}
