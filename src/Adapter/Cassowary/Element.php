<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use PhpTui\Cassowary\Expression;
use PhpTui\Cassowary\Variable;

final class Element
{
    private function __construct(public Variable $start, public Variable $end)
    {
    }

    public static function empty(): self
    {
        return new self(
            Variable::new(),
            Variable::new(),
        );
    }

    public function start(): Variable
    {
        return $this->start;
    }

    public function end(): Variable
    {
        return $this->end;
    }

    public function size(): Expression
    {
        return $this->end->sub($this->start);
    }
}
