<?php

namespace PhpTui\Tui\Widget\Canvas\Grid;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Widget\Canvas\CanvasGrid;
use PhpTui\Tui\Widget\Canvas\FgBgColor;
use PhpTui\Tui\Widget\Canvas\Layer;
use PhpTui\Tui\Widget\Canvas\Resolution;

final class CharGrid extends CanvasGrid
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
    ) {
    }

    public static function new(int $width, int $height, string $cellChar): self
    {
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
            chars: $this->cells,
            colors: array_map(fn (Color $color) => new FgBgColor($color, AnsiColor::Reset), $this->colors),
        );
    }

    public function reset(): void
    {
        $this->cells = array_map(fn ($_) => ' ', $this->cells);
        $this->colors = array_map(fn ($_) => AnsiColor::Reset, $this->colors);
    }

    public function paint(Position $position, Color $color): void
    {
        $index = $position->y * $this->resolution->width + $position->x;
        if (isset($this->cells[$index])) {
            $this->cells[$index] = $this->cellChar;
        }
        if (isset($this->colors[$index])) {
            $this->colors[$index] = $color;
        }
    }
}
