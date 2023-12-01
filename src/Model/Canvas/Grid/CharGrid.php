<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas\Grid;

use PhpTui\Tui\Model\Canvas\CanvasGrid;
use PhpTui\Tui\Model\Canvas\FgBgColor;
use PhpTui\Tui\Model\Canvas\Layer;
use PhpTui\Tui\Model\Canvas\Resolution;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\Color;
use PhpTui\Tui\Model\Position\Position;

final class CharGrid extends CanvasGrid
{
    /**
     * @param string[] $cells
     * @param Color[] $colors
     */
    private function __construct(
        private readonly Resolution $resolution,
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
            colors: array_map(static fn (Color $color): FgBgColor => new FgBgColor($color, AnsiColor::Reset), $this->colors),
        );
    }

    public function reset(): void
    {
        $this->cells = array_map(static fn (): string => ' ', $this->cells);
        $this->colors = array_map(static fn (): AnsiColor => AnsiColor::Reset, $this->colors);
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
