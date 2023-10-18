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
        private int $width,
        private int $height,
        private array $cells,
        private array $colors,
        private string $cellChar
    ){}

    public static function new(int $width, int $height, string $cellChar): self {
        $length = $width * $height;
        return new self(
            $width,
            $height,
            array_fill(0, $length, ' '),
            array_fill(0, $length, AnsiColor::Reset),
            $cellChar,
        );
    }
}
