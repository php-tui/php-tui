<?php

namespace PhpTui\Tui\Example\Demo\Page;

class Download
{
    public function __construct(
        public int $size,
        public float $downloaded = 0.0,
    ) {
    }

    public function ratio(): float
    {
        if ($this->size === 0) {
            return 0;
        }
        return $this->downloaded / $this->size;
    }
}
