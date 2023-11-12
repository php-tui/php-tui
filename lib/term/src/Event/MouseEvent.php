<?php

namespace PhpTui\Term\Event;

use PhpTui\Term\Event;
use PhpTui\Term\MouseButton;
use PhpTui\Term\MouseEventKind;

final class MouseEvent implements Event
{
    private function __construct(private MouseEventKind $kind, private MouseButton $button, private int $column, private int $row, private int $modifiers)
    {
    }

    public static function new(MouseEventKind $kind, MouseButton $button, int $column, int $row, int $modifiers): self {
        return new self(
            kind: $kind,
            button: $button,
            column: $column,
            row: $row,
            modifiers: $modifiers
        );
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
    
}
