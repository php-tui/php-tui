<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\KeyCode;
use PhpTui\Term\KeyModifiers;

class KeyEvent implements Event
{
    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    private function __construct(public KeyCode $code, public int $modifiers)
    {
    }

    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    public static function new(KeyCode $keyCode, int $modifiers = KeyModifiers::NONE): self
    {
        return new self($keyCode, $modifiers);
    }
}
