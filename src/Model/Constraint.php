<?php

namespace DTL\PhpTui\Model;

use DTL\PhpTui\Model\Constraint\LengthConstraint;
use DTL\PhpTui\Model\Constraint\MaxConstraint;

use DTL\PhpTui\Model\Constraint\MinConstraint;
use DTL\PhpTui\Model\Constraint\PercentageConstraint;

/**
 * Implemented this "interface" as an abstract class
 * to allow easy access to factory methods
 */
abstract class Constraint
{
    public static function length(int $length): LengthConstraint
    {
        return new LengthConstraint($length);
    }

    public static function percentage(int $percentage): PercentageConstraint
    {
        return new PercentageConstraint($percentage);
    }

    public static function max(int $max): MaxConstraint
    {
        return new MaxConstraint($max);
    }

    public static function min(int $min): MinConstraint
    {
        return new MinConstraint($min);
    }
}
