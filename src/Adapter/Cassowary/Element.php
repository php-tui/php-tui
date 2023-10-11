<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\Cassowary\Variable;

final class Element
{
    public function __construct(public Variable $x, public Variable $y, public Variable $width, public Variable $height)
    {
    }

    public static function empty(): self
    {
        return new self(
            new Variable(0.0),
            new Variable(0.0),
            new Variable(0.0),
            new Variable(0.0),
        );
    }
}
