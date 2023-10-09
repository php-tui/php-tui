<?php

namespace DTL\PhpTui\Model;

final class Area
{
    public function __construct(
        public int $x,
        public int $y,
        public int $width,
        public int $height,
    ) {
    }

    public function area(): int
    {
        return $this->width * $this->height;
    }

}
