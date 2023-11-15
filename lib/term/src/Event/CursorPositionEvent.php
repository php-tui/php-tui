<?php

declare(strict_types=1);

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;

class CursorPositionEvent implements Event
{
    public function __construct(
        public readonly int $x,
        public readonly int $y
    ) {
    }

    public function __toString(): string
    {
        return sprintf('CursorPosition(%d, %d)', $this->x, $this->y);
    }
}
