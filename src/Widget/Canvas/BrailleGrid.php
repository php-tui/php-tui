<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Widget\BrailleSet;
use IntlChar;

final class BrailleGrid extends Grid
{
    /** 
     * @param int[] $codePoints
     * @param Color[] $colors
     */
    private function __construct(
        private Resolution $resolution,
        private array $codePoints,
        private array $colors
    ){}

    public static function new(int $width, int $height): self {
        $length = $width * $height;
        return new self(
            new Resolution($width, $height),
            array_fill(0, $length, BrailleSet::BLANK),
            array_fill(0, $length, AnsiColor::Reset),
        );
    }

    public function resolution(): Resolution
    {
        return $this->resolution;
    }

    public function save(): Layer
    {
        return new Layer(
            chars: array_map(fn (int $point) => IntlChar::chr($point), $this->codePoints),
            colors: array_map(fn (Color $color) => new FgBgColor($color, AnsiColor::Reset), $this->colors),
        );
    }

    public function reset(): void
    {
        $this->codePoints = array_map(fn ($_) => BrailleSet::BLANK, $this->codePoints);
        $this->colors = array_map(fn ($_) => AnsiColor::Reset, $this->colors);
    }

    public function paint(Position $position, Color $color): void
    {
        $index = intval($position->y / 4 * $this->resolution->x + $position->x / 2);
        if (isset($this->codePoints[$index])) {
            $this->codePoints[$index] |= BrailleSet::DOTS[$position->y % 4][$position->x % 2];
        }
        if (isset($this->colors[$index])) {
            $this->colors[$index] = $color;
        }
    }
}
