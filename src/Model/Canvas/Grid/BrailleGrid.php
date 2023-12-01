<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas\Grid;

use IntlChar;
use PhpTui\Tui\Model\Canvas\CanvasGrid;
use PhpTui\Tui\Model\Canvas\FgBgColor;
use PhpTui\Tui\Model\Canvas\Layer;
use PhpTui\Tui\Model\Canvas\Resolution;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\Color;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Symbol\BrailleSet;

final class BrailleGrid extends CanvasGrid
{
    /**
     * @var array <int, ?string>
     */
    private array $brailleCharCache = [];

    /**
     * @param int[] $codePoints
     * @param Color[] $colors
     */
    private function __construct(
        private readonly int $width,
        private readonly int $height,
        private array $codePoints,
        private array $colors
    ) {
    }

    public static function new(int $width, int $height): self
    {
        $length = $width * $height;

        return new self(
            $width,
            $height,
            array_fill(0, $length, BrailleSet::BLANK),
            array_fill(0, $length, AnsiColor::Reset),
        );
    }

    public function resolution(): Resolution
    {
        return new Resolution($this->width * 2, $this->height * 4);
    }

    public function save(): Layer
    {
        $chars = array_map(function (int $point) {
            return $this->brailleCharCache[$point] ??= IntlChar::chr($point);
        }, $this->codePoints);

        $colors = array_map(static fn (Color $color): FgBgColor => new FgBgColor($color, AnsiColor::Reset), $this->colors);

        return new Layer(chars: $chars, colors: $colors);
    }

    public function reset(): void
    {
        $this->codePoints = array_map(static fn (): int => BrailleSet::BLANK, $this->codePoints);
        $this->colors = array_map(static fn (): AnsiColor => AnsiColor::Reset, $this->colors);
    }

    public function paint(Position $position, Color $color): void
    {
        $index = ((int) ($position->y / 4)) * $this->width + (int) ($position->x / 2);
        if (isset($this->codePoints[$index])) {
            $this->codePoints[$index] |= BrailleSet::DOTS[$position->y % 4][$position->x % 2];
        }
        if (isset($this->colors[$index])) {
            $this->colors[$index] = $color;
        }
    }
}
