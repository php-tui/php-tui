<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Widget;

use PhpTui\Tui\Model\Style;

class StyledGrapheme
{
    public function __construct(
        public string $symbol,
        public Style $style
    ) {
    }

    public function symbolWidth(): int
    {
        return mb_strlen($this->symbol);
    }
}
