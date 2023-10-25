<?php

namespace PhpTui\Term;

enum KeyEventKind
{
    case Press;
    case Repeat;
    case Release;
}
