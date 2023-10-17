<?php

namespace DTL\PhpTui\Model\Widget;

use DTL\PhpTui\Model\Style;

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
