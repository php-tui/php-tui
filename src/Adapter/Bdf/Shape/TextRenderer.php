<?php

namespace PhpTui\Tui\Adapter\Bdf\Shape;

use PhpTui\BDF\BdfGlyph;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;

class TextRenderer implements ShapePainter
{
    public function __construct(
        private FontRegistry $registry,
    ) {
    }

    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof TextShape) {
            return;
        }

        $font = $this->registry->get($shape->font);
        $charOffset = 0;
        foreach (str_split($shape->text) as $char) {
            $glyph = $font->codePoint(ord($char));

            $grid = self::buildGrid($shape, $glyph);
            $charOffset += self::renderChar($shape, $painter, $charOffset, $grid, $glyph);
        }
    }

    /**
     * @return list<array<int,bool>>
     */
    private static function buildGrid(TextShape $shape, BdfGlyph $glyph): array
    {
        $grid = [];
        $y = 0;
        foreach (array_reverse($glyph->bitmap) as $row) {
            $xbit = 1;
            for ($i = $glyph->boundingBox->size->width + 1; $i >= 0; $i--) {
                $x = $i + $shape->position->x;
                $grid[$y][$x] = ($row & $xbit) > 0;
                $xbit = $xbit << 1;
            }
            $y++;
        }
        return $grid;
    }

    /**
     * @param array<int,array<int,bool>> $grid
     */
    private static function renderChar(
        TextShape $shape,
        Painter $painter,
        float $charOffset,
        array $grid,
        BdfGlyph $glyph
    ): float {
        $xStep = $painter->context->xBounds->length() / $painter->resolution->width;
        $yStep = $painter->context->yBounds->length() / $painter->resolution->height;

        $charWidth = $shape->scaleX;
        $charHeight = $shape->scaleY;

        $yOffset = $glyph->boundingBox->offset->y * $shape->scaleY;

        $points = [];
        foreach ($grid as $row) {
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

                for ($yF = $y1; $yF < $y2; $yF+=$yStep) {
                    for ($xF = $x1; $xF < $x2; $xF+=$xStep) {
                        $point = $painter->getPoint(FloatPosition::at(
                            $charOffset + $shape->position->x + $xF,
                            $shape->position->y + $yF,
                        ));
                        $points[] = $point;
                    }
                }
            }
        }

        foreach ($points as $point) {
            if (null === $point) {
                continue;
            }

            $painter->paint($point, $shape->color);
        }

        return $glyph->boundingBox->size->width * $shape->scaleX;
    }
}
