<?php

declare(strict_types=1);

namespace PhpTui\Term\Action;

use PhpTui\Term\Action;

class EnableMouseCapture implements Action
{
    public function __construct(public readonly bool $enable)
    {
    }

    public function __toString(): string
    {
        return sprintf('EnableMouseCapture(%s)', $this->enable ? 'true' : 'false');
    }
}
