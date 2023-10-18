<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Color;

final class CharGrid extends Grid
{
    /** 
     * @param string[] $cells
     * @param Color[] $colors
     */
    private function __construct(
        private Resolution $resolution,
        private array $cells,
        private array $colors,
        private string $cellChar
    ){}

    public static function new(int $width, int $height, string $cellChar): self {
        $length = $width * $height;
        return new self(
            new Resolution($width, $height),
            array_fill(0, $length, ' '),
            array_fill(0, $length, AnsiColor::Reset),
            $cellChar,
        );
    }

    public function resolution(): Resolution
    {
        return $this->resolution;
    }

    public function save(): Layer
    {
        return new Layer(
            string: implode('', $this->cells),
            colors: array_map(fn (Color $color) => new FgBgColor($color, AnsiColor::Reset), $this->colors),
        );
    }

    public function reset(): void
    {
        $this->cells = array_map(fn ($_) => ' ', $this->cells);
        $this->colors = array_map(fn ($_) => AnsiColor::Reset, $this->colors);
    }
}
