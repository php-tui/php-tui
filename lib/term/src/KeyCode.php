<?php

declare(strict_types=1);

namespace PhpTui\Term;

enum KeyCode
{
    /// Backspace key.
    case Backspace;
    /// Enter key.
    case Enter;
    /// Left arrow key.
    case Left;
    /// Right arrow key.
    case Right;
    /// Up arrow key.
    case Up;
    /// Down arrow key.
    case Down;
    /// Home key.
    case Home;
    /// End key.
    case End;
    /// Page up key.
    case PageUp;
    /// Page down key.
    case PageDown;
    /// Tab key.
    case Tab;
    /// Shift + Tab key.
    case BackTab;
    /// Delete key.
    case Delete;
    /// Insert key.
    case Insert;
    /// F key.
    ///
    /// `KeyCode::F(1)` represents F1 key; etc.
    case FKey;
    /// A character.
    ///
    /// `KeyCode::Char('c')` represents `c` character; etc.
    case Char;
    /// Escape key.
    case Esc;
}
