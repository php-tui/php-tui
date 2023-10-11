<?php

namespace DTL\Cassowary;

class SolverUtil
{
    private const NEAR_ZERO = 1E-8;

    public static function nearZero(float $value): bool
    {
        if ($value < 0.0) {
            return -$value < self::NEAR_ZERO;
        }

        return $value < self::NEAR_ZERO;
    }

}
