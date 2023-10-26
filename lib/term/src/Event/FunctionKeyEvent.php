<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\KeyEventKind;
use PhpTui\Term\KeyModifiers;

class FunctionKeyEvent implements KeyEvent
{
    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    private function __construct(public int $number, public int $modifiers, public KeyEventKind $kind)
    {
    }

    public function __toString(): string
    {
        return sprintf(
            'FunctionKey(number: %s, modifier: %s, kind: %s)',
            $this->number,
            KeyModifiers::toString($this->modifiers),
            $this->kind->name
        );
    }

    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    public static function new(int $number, int $modifiers = KeyModifiers::NONE, KeyEventKind $kind = KeyEventKind::Press): self
    {
        return new self($number, $modifiers, $kind);
    }
}
