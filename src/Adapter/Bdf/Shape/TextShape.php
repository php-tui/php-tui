<?php

namespace PhpTui\Tui\Adapter\Bdf\Shape;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Renders text on the canvas.
 */
class TextShape implements Shape
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
        public  float $scaleX = 1.0,
        /**
         * Verttical scale of the font
         */
        public  float $scaleY = 1.0,
    ) {
    }
}
