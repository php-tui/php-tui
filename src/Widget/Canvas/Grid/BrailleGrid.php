<?php

namespace PhpTui\Tui\Widget\Canvas\Grid;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget\BrailleSet;
use IntlChar;
use PhpTui\Tui\Widget\Canvas\CanvasGrid;
use PhpTui\Tui\Widget\Canvas\FgBgColor;
use PhpTui\Tui\Widget\Canvas\Layer;
use PhpTui\Tui\Widget\Canvas\Resolution;

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
        private int $width,
        private int $height,
        private array $codePoints,
        private array $colors
    ) {
        $this->cacheBrailleChars();
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
            return $this->brailleCharCache[$point] ?? ($this->brailleCharCache[$point] = IntlChar::chr($point));
        }, $this->codePoints);

        $colors = array_map(fn (Color $color) => new FgBgColor($color, AnsiColor::Reset), $this->colors);

        return new Layer(chars: $chars, colors: $colors);
    }

    public function reset(): void
    {
        $this->codePoints = array_map(fn () => BrailleSet::BLANK, $this->codePoints);
        $this->colors = array_map(fn () => AnsiColor::Reset, $this->colors);
    }

    public function paint(Position $position, Color $color): void
    {
        $index = (intval($position->y / 4)) * $this->width + intval($position->x / 2);
        if (isset($this->codePoints[$index])) {
            $this->codePoints[$index] |= BrailleSet::DOTS[$position->y % 4][$position->x % 2];
        }
        if (isset($this->colors[$index])) {
            $this->colors[$index] = $color;
        }
    }

    private function cacheBrailleChars(): void
    {
        [$from, $to] = BrailleSet::RANGE;

        for ($i = $from; $i <= $to; $i++) {
            $this->brailleCharCache[$i] = IntlChar::chr($i);
        }
    }
}
