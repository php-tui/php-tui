<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;

class CursorPositionEvent implements Event
{
    public function __construct(public int $x, public int $y)
    {
    }

    public function __toString(): string
    {
        return sprintf('CursorPosition(%d, %d)', $this->x, $this->y);
    }
}
