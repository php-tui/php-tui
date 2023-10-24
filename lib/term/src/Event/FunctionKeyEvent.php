<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;

class FunctionKeyEvent implements KeyEvent
{
    private function __construct(public int $number)
    {
    }
    public static function new(int $number): self
    {
        return new self($number);
    }

}
