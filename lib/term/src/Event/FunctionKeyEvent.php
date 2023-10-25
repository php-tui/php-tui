<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\KeyModifiers;

class FunctionKeyEvent implements KeyEvent
{
    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    private function __construct(public int $number, public int $modifiers)
    {
    }

    public function __toString(): string
    {
        return sprintf('FunctionKey(number: %s, modifier: %d)', $this->number, $this->modifiers);
    }

    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    public static function new(int $number, int $modifiers = KeyModifiers::NONE): self
    {
        return new self($number, $modifiers);
    }
}
