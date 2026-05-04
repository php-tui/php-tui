<?php

declare(strict_types=1);

namespace PhpTui\BDF;

final readonly class BdfCoord
{
    public function __construct(
        public int $x,
        public int $y
    ) {
    }
}
