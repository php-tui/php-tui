<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\KeyModifiers;

final class CharKeyEvent implements KeyEvent
{
    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    private function __construct(public string $char, public int $modifiers)
    {
    }

    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    public static function new(string $char, int $modifiers = KeyModifiers::NONE): self
    {
        return new self($char, $modifiers);
    }

    public function __toString(): string
    {
        return sprintf('CharKeyEvent(char: %s, modifiers: %s)', $this->char, $this->modifiers);
    }
}
