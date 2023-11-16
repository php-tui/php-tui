<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final class BdfBoundingBox
{
    public function __construct(
        public readonly BdfSize $size,
        public readonly BdfCoord $offset
    ) {
    }

    public static function empty(): self
    {
        return new self(new BdfSize(0, 0), new BdfCoord(0, 0));
    }

    public static function fromScalars(int $width, int $height, int $x, int $y): self
    {
        return new self(new BdfSize($width, $height), new BdfCoord($x, $y));
    }
}
