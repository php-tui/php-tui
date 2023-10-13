<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\Cassowary\Expression;
use DTL\Cassowary\Variable;

final class Element
{
    public function __construct(public Variable $start, public Variable $end)
    {
    }

    public static function empty(): self
    {
        return new self(
            new Variable(0.0),
            new Variable(0.0),
        );
    }

    public function left(): Variable
    {
        return $this->x;
    }

    public function top(): Variable
    {
        return $this->y;
    }

    public function right(): Expression
    {
        return $this->x->add($this->width);
    }

    public function bottom(): Expression
    {
        return $this->y->add($this->height);
    }
}
