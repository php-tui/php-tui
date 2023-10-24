<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\KeyCode;

class KeyEvent implements Event
{
    private function __construct(public KeyCode $code)
    {
    }

    public static function new(KeyCode $keyCode): self
    {
        return new self($keyCode);
    }
}
