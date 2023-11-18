<?php

namespace PhpTui\Tui\Model\Widget;

use RuntimeException;

final class FractionalPosition 
{
    private function __construct(
        public float $x,
        public float $y
    )
    {
        if ($x < 0 || $x > 1 || $y < 0 || $y > 1) {
            throw new RuntimeException(sprintf(
                'Fractional axis must be between 0 and 1 got [%d, %d]',
                $x, $y
            ));
        }
    }

    public static function at(float $x, float $y): self
    {
        return new self($x, $y);
    }
}
