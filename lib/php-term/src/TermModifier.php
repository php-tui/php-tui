<?php

namespace DTL\PhpTerm;

enum TermModifier
{
    case Reset;
    case Bold;
    case Dim;
    case Italic;
    case Underline;
    case Strike;
    case Blink;
    case Hidden;
    case Reverse;
}
