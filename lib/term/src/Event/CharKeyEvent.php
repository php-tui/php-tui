<?php

declare(strict_types=1);

namespace PhpTui\Term\Event;

use PhpTui\Term\KeyModifiers;

final class CharKeyEvent implements KeyEvent
{
    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    private function __construct(
        public readonly string $char,
        public readonly int $modifiers
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'CharKeyEvent(char: %s, modifiers: %s)',
            $this->char,
            KeyModifiers::toString($this->modifiers)
        );
    }

    /**
     * @param int-mask-of<KeyModifiers::*> $modifiers
     */
    public static function new(string $char, int $modifiers = KeyModifiers::NONE): self
    {
        return new self($char, $modifiers);
    }
}
