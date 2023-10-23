<?php

namespace PhpTui\Term;

enum Attribute
{
    case Reset;
    case Bold;
    case Dim;
    case Italic;
    case Underline;
    case Strike;
    case SlowBlink;
    case RapidBlink;
    case Hidden;
    case Reverse;
}
