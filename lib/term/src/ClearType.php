<?php

declare(strict_types=1);

namespace PhpTui\Term;

enum ClearType
{
    case All;
    case FromCursorDown;
    case Purge;
    case CurrentLine;
    case FromCursorUp;
    case UntilNewLine;
}
