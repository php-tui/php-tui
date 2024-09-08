<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Bdf\Shape;

use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FloatPosition;

/**
 * Renders text on the canvas.
 *
 * This widget requires a bitmap font in the BDF format.
 * You can use the `PhpTui\Tui\Adapter\Bdf\FontRegistry` to
 *  load and manage fonts. It has a default font built in.
 */
final class TextShape implements Shape
{
    public function __construct(
        /**
         * Font name as it is known in the font registry
         */
        public readonly string $font,
        /**
         * Text to render
         */
        public readonly string $text,
        /**
         * Color of the text
         */
        public Color $color,
        /**
         * Position of the text (bottom left corner)
         */
        public readonly FloatPosition $position,

        /**
         * Horizontal scale of the font
         */
        public float $scaleX = 1.0,
        /**
         * Verttical scale of the font
         */
        public float $scaleY = 1.0,
    ) {
    }
}
