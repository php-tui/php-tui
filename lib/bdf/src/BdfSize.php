<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final readonly class BdfSize
{
    public function __construct(
        public int $width,
        public int $height
    ) {
    }
}
