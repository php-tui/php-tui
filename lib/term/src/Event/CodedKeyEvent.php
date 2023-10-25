<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\KeyCode;
use PhpTui\Term\KeyModifiers;

class CodedKeyEvent implements KeyEvent
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

    public function __toString(): string
    {
        return sprintf('CodedKeyEvent(code: %s, modifiers: %s)', $this->code->name, $this->modifiers);
    }
}
