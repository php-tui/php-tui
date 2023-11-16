<?php

declare(strict_types=1);

namespace PhpTui\Term;

enum KeyEventKind
{
    case Press;
    case Repeat;
    case Release;
}
