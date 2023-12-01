<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Style;

interface Styleable
{
    public function patchStyle(Style $style): self;
}
