<?php

namespace DTL\PhpTui\Model;

enum AnsiColor implements Color
{
    case Reset;
    case Black;
    case Red;
    case Green;
    case Yellow;
    case Blue;
    case Magenta;
    case Cyan;
    case Gray;
    case DarkGray;
    case LightRed;
    case LightGreen;
    case LightYellow;
    case LightBlue;
    case LightMagenta;
    case LightCyan;
    case White;
}
