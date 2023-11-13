<?php

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

/**
 * Command to asynchonously request the cursor position from the terminal.
 * See the corresponding PhpTui\Term\Event\CursorPositionEvent
 */
final class RequestCursorPosition implements Action
{
    public function __toString(): string
    {
        return sprintf('RequestCursorPosition()');
    }
}
