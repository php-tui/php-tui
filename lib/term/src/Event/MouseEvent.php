<?php

declare(strict_types=1);

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\MouseButton;
use PhpTui\Term\MouseEventKind;

final class MouseEvent implements Event
{
    private function __construct(
        public readonly MouseEventKind $kind,
        public readonly MouseButton $button,
        public readonly int $column,
        public readonly int $row,
        public readonly int $modifiers
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'MouseEvent(kind: %s, button: %s, col: %d, row: %d, modifiers: %d)',
            $this->kind->name,
            $this->button->name,
            $this->column,
            $this->row,
            $this->modifiers
        );
    }

    public static function new(MouseEventKind $kind, MouseButton $button, int $column, int $row, int $modifiers): self
    {
        return new self(
            kind: $kind,
            button: $button,
            column: $column,
            row: $row,
            modifiers: $modifiers
        );
    }

}
