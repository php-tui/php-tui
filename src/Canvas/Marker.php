<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

enum Marker
{
    /**
     *One point per cell in shape of dot ("•")
     */
    case Dot;

    /**
     * One point per cell in shape of a block ("█")
     */
    case Block;
    /**
     *  One point per cell in the shape of a bar ("▄")
     */
    case Bar;
    /**
     * Use the [Unicode Braille Patterns](https://en.wikipedia.org/wiki/Braille_Patterns) block to
     * represent data points.
     *
     * This is a 2x4 grid of dots, where each dot can be either on or off.
     *
     * Note: Support for this marker is limited to terminals and fonts that support Unicode
     * Braille Patterns. If your terminal does not support this, you will see unicode replacement
     * characters (�) instead of Braille dots.
     */
    case Braille;
    /**
     * Use the unicode block and half block characters ("█", "▄", and "▀") to represent points in
     * a grid that is double the resolution of the terminal. Because each terminal cell is
     * generally about twice as tall as it is wide, this allows for a square grid of pixels.
     */
    case HalfBlock;
}
