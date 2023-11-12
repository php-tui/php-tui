<?php

namespace PhpTui\Term;

enum MouseEventKind
{
    /// Pressed mouse button. Contains the button that was pressed.
    case Down;
    /// Released mouse button. Contains the button that was released.
    case Up;
    /// Moved the mouse cursor while pressing the contained mouse button.
    case Drag;
    /// Moved the mouse cursor while not pressing a mouse button.
    case Moved;
    /// Scrolled mouse wheel downwards (towards the user).
    case ScrollDown;
    /// Scrolled mouse wheel upwards (away from the user).
    case ScrollUp;
    /// Scrolled mouse wheel left (mostly on a laptop touchpad).
    case ScrollLeft;
    /// Scrolled mouse wheel right (mostly on a laptop touchpad).
    case ScrollRight;
}
