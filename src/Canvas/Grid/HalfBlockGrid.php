<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas\Grid;

use PhpTui\Tui\Canvas\CanvasGrid;
use PhpTui\Tui\Color\FgBgColor;
use PhpTui\Tui\Canvas\Layer;
use PhpTui\Tui\Canvas\Resolution;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Symbol\BlockSet;

final class HalfBlockGrid extends CanvasGrid
{
    public function __construct(
        private readonly Resolution $resolution,
        /**
         * @var list<list<Color>>
         */
        private array $pixels,
    ) {
    }

    public static function new(int $width, int $height): self
    {
        if ($width <= 0 || $height <= 0) {
            return new self(new Resolution($width, $height), []);
        }
        $length = $width * $height;

        return new self(
            new Resolution($width, $height),
            array_map(
                static fn (): array => array_map(
                    static fn (): AnsiColor => AnsiColor::Reset,
                    range(1, $width)
                ),
                range(1, $height * 2)
            ),
        );
    }

    public function resolution(): Resolution
    {
        return new Resolution(
            $this->resolution->width,
            $this->resolution->height * 2,
        );
    }

    public function save(): Layer
    {
        $paired = [];
        while ($this->pixels) {
            $upper = array_shift($this->pixels);
            $lower = array_shift($this->pixels);

            if (!$lower) {
                break;
            }

            $paired = array_merge($paired, array_map(null, $upper, $lower));
        }

        $chars = [];
        $chars = array_map(function (array $pair): string {
            [$upper, $lower] = $pair;
            if ($upper === AnsiColor::Reset && $lower === AnsiColor::Reset) {
                return ' ';
            }
            if ($upper !== AnsiColor::Reset && $lower === AnsiColor::Reset) {
                return BlockSet::UPPER_HALF;
            }
            /** @phpstan-ignore-next-line */
            if ($upper === AnsiColor::Reset && $lower !== AnsiColor::Reset) {
                return BlockSet::LOWER_HALF;
            }
            if ($upper === $lower) {
                return BlockSet::FULL;
            }

            return BlockSet::UPPER_HALF;
        }, $paired);
        $colors = array_map(function (array $pair): FgBgColor {
            [$upper, $lower] = $pair;
            if ($upper === AnsiColor::Reset && $lower === AnsiColor::Reset) {
                return new FgBgColor(AnsiColor::Reset, AnsiColor::Reset);
            }
            // upper half has been set: set the foreground color
            if ($upper !== AnsiColor::Reset && $lower === AnsiColor::Reset) {
                return new FgBgColor($upper, AnsiColor::Reset);
            }
            // lower half has been set: set the foreground color
            /** @phpstan-ignore-next-line */
            if ($upper === AnsiColor::Reset && $lower !== AnsiColor::Reset) {
                return new FgBgColor($lower, AnsiColor::Reset);
            }

            // both set, it will be a block with one color
            return new FgBgColor($upper, $lower);
        }, $paired);

        return new Layer($chars, $colors);

    }

    public function reset(): void
    {
    }

    public function paint(Position $position, Color $color): void
    {
        if (isset($this->pixels[$position->y][$position->x])) {
            $this->pixels[$position->y][$position->x] = $color;
        }
    }
}
