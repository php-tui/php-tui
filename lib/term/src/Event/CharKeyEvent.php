<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\KeyModifiers;

final class CharKeyEvent implements Event
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
}
