<?php

namespace DTL\PhpTui\Model;

final class Area
{
    public function __construct(
        public Position $position,
        public int $width,
        public int $height,
    ) {
    }

    public static function fromPrimatives(int $x, int $y, int $width, int $height): self
    {
        return new self(new Position($x, $y), $width, $height);
    }

    public function area(): int
    {
        return $this->width * $this->height;
    }

}
