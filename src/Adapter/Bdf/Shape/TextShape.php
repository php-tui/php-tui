<?php

namespace PhpTui\Tui\Adapter\Bdf\Shape;

use PhpTui\BDF\BdfFont;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Renders text on the canvas.
 * This widget requires a bitmap font in the BDF format.
 * You can use the `PhpTui\Tui\Adapter\Bdf\FontRegistry` to
 *  load and manage fonts. It has a default font built in.
 */
class TextShape implements Shape
{
    public function __construct(
        /**
         * BDF font
         */
        private BdfFont $font,
        /**
         * Text to render
         */
        public readonly string $text,
        /**
         * Color of the text
         */
        private Color $color,
        /**
         * Position of the text (bottom left corner)
         */
        public readonly FloatPosition $position,
    ) {
    }

    public function draw(Painter $painter): void
    {
        $charOffset = 0;
        foreach (str_split($this->text) as $char) {
            $glyph = $this->font->codePoint(ord($char));
            $y = $this->position->y;

            $lines = [];
            foreach (array_reverse($glyph->bitmap) as $row) {
                $xbit = 1;
                $offsets = [];
                for ($i = $glyph->boundingBox->size->width + 1; $i >= 0; $i--) {
                    if (($row & $xbit) > 0) {
                        $offsets[] = $i + $charOffset + $this->position->x;
                    }
                    $xbit = $xbit << 1;
                }
                foreach ($offsets as $offset) {
                    $point = $painter->getPoint(FloatPosition::at(
                        $offset,
                        $y + $glyph->boundingBox->offset->y,
                    ));
                    if (null === $point) {
                        continue;
                    }
                    $painter->paint($point, $this->color);
                }

                $y++;
            }
            $charOffset += $glyph->boundingBox->size->width;
        }
    }
}
