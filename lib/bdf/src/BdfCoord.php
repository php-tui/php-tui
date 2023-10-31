<?php

namespace PhpTui\BDF;

class BdfCoord
{
    public function __construct(
        public readonly int $x,
        public readonly int $y
    )
    {
    }

}
