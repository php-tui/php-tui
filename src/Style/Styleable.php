<?php

declare(strict_types=1);

namespace PhpTui\Tui\Style;

use PhpTui\Tui\Style\Style;

interface Styleable
{
    public function patchStyle(Style $style): self;
}
