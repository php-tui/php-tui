<?php

namespace PhpTui\BDF;

final class BdfSize
{
    public function __construct(
        public readonly int $width,
        public readonly int $height
    ) {
    }
}
