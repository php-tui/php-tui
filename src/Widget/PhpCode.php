<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Widget;

class PhpCode implements Widget
{
    public function __construct(
        public string $code,
    ) {
    }
}
