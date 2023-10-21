<?php

namespace DTL\PhpTerm\Action;

use DTL\PhpTerm\Action;

final class CursorShow  implements Action
{
    public function __construct(public readonly bool $show)
    {
    }

    public function __toString(): string
    {
        return sprintf('CursorShow(%s)', $this->show ? 'true':'false');
    }
}
