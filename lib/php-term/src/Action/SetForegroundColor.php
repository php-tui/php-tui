<?php

namespace DTL\PhpTerm\Action;

use DTL\PhpTerm\Colors;
use DTL\PhpTerm\Action;

final class SetForegroundColor implements Action
{
    public function __construct(public readonly Colors $color)
    {
    }

    public function __toString(): string
    {
        return sprintf('SetForegroundColor(%s)', $this->color->name);
    }
}
