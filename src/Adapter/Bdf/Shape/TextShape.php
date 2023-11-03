<?php

namespace PhpTui\Tui\Adapter\Bdf\Shape;

use PhpTui\BDF\BdfFont;
use PhpTui\BDF\BdfGlyph;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;
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

        /**
         * Horizontal scale of the font
         */
        private float $scaleX = 1.0,
        /**
         * Verttical scale of the font
         */
        private float $scaleY = 1.0,
    ) {
    }

    public function draw(Painter $painter): void
    {
        $charOffset = 0;
        foreach (str_split($this->text) as $char) {
            $glyph = $this->font->codePoint(ord($char));

            $grid = $this->buildGrid($glyph);
            $charOffset += $this->renderChar($painter, $charOffset, $grid, $glyph);
        }

    }

    /**
     * @return list<array<int,bool>>
     */
    private function buildGrid(BdfGlyph $glyph): array
    {
        $grid = [];
        $y = 0;
        foreach (array_reverse($glyph->bitmap) as $row) {
            $xbit = 1;
            for ($i = $glyph->boundingBox->size->width + 1; $i >= 0; $i--) {
                $x = $i + $this->position->x;
                if (($row & $xbit) > 0) {
                    $grid[$y][$x] = true;
                } else {
                    $grid[$y][$x] = false;
                }
                $xbit = $xbit << 1;
            }
            $y++;
        }
        return $grid;
    }

    /**
     * @param array<int,array<int,bool>> $grid
     */
    private function renderChar(
        Painter $painter,
        float $charOffset,
        array $grid,
        BdfGlyph $glyph
    ): float {
        $charWidth = 1 * $this->scaleX;
        $charHeight = 1 * $this->scaleY;
        $renderedWidth = 0;

        $yOffset = $glyph->boundingBox->offset->y * $this->scaleY;
        $points = [];
        foreach ($grid as $y => $row) {
            $y1 = $yOffset;
            $y2 = $yOffset + $charHeight;
            $yOffset += abs($y2 - $y1);

            $xOffset = 1;
            foreach (array_reverse($row) as $render) {
                $x1 = $xOffset;
                $x2 = $xOffset + $charWidth;
                $xOffset += abs($x2 - $x1);

                if ($render === false) {
                    continue;
                }

                for ($yF = $y1; $yF < $y2; $yF++) {
                    for ($xF = $x1; $xF < $x2; $xF++) {
                        $points[] = $painter->getPoint(FloatPosition::at(
                            $charOffset + $this->position->x + $xF,
                            $this->position->y + $yF,
                        ));
                    }
                }
            }
        }

        $maxX = null;
        foreach ($points as $point) {
            if (null === $point) {
                continue;
            }
            $painter->paint($point, $this->color);
        }

        return $glyph->boundingBox->size->width * $this->scaleX;
    }
}
