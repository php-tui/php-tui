<?php

declare(strict_types=1);

namespace PhpTui\Tui\Text;

use PhpTui\Tui\Style\Style;

final class StyledGrapheme
{
    public function __construct(
        public string $symbol,
        public Style $style
    ) {
    }

    /**
     * @return int<0,max>
     */
    public function symbolWidth(): int
    {
        return mb_strwidth($this->symbol);
    }
}
