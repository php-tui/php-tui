<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display\Backend;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Backend;
use PhpTui\Tui\Display\BufferUpdates;
use PhpTui\Tui\Display\ClearType;
use PhpTui\Tui\Position\Position;

final class DummyBackend implements Backend
{
    /**
     * @var array<int,array<int,string>>
     */
    private array $grid = [];

    private ?string $flushed = null;

    /**
     * @param int<0,max> $height
     * @param int<0,max> $width
     */
    public function __construct(private int $width, private int $height, private Position $cursorPosition = new Position(0, 0))
    {
        $this->fillGrid($width, $height);
    }

    public function flushed(): ?string
    {
        return $this->flushed;
    }

    public function size(): Area
    {
        return Area::fromScalars(0, 0, $this->width, $this->height);
    }

    public function draw(BufferUpdates $updates): void
    {
        foreach ($updates as $update) {
            $this->grid[$update->position->y][$update->position->x] = $update->cell->char;
        }
    }

    public function toString(): string
    {
        return implode("\n", array_map(static function (array $cells): string {
            return implode('', $cells);
        }, $this->grid));
    }

    /**
     * @return string[]
     */
    public function toLines(): array
    {
        return array_map(static function (array $cells): string {
            return implode('', $cells);
        }, $this->grid);
    }

    /**
     * @param int<0,max> $width
     * @param int<0,max> $height
     */
    public static function fromDimensions(int $width, int $height): self
    {

        return new self($width, $height);
    }

    /**
     * @param int<0,max> $width
     * @param int<0,max> $height
     */
    public function setDimensions(int $width, int $height): void
    {
        $this->fillGrid($width, $height);
        $this->width = $width;
        $this->height = $height;
    }

    public function flush(): void
    {
        $this->flushed = $this->toString();
    }

    public function clearRegion(ClearType $type): void
    {
        match ($type) {
            ClearType::ALL => $this->fillGrid($this->width, $this->height),
            ClearType::AfterCursor => $this->clearLine($this->cursorPosition->y),
        };
    }

    public function cursorPosition(): Position
    {
        return $this->cursorPosition;
    }

    /**
     * @param int<0,max> $linesAfterCursor
     */
    public function appendLines(int $linesAfterCursor): void
    {
        $this->cursorPosition = $this->cursorPosition->change(static fn (int $x, int $y): array => [0, $y + $linesAfterCursor]);
    }

    public function moveCursor(Position $position): void
    {
        $this->cursorPosition = $position;
    }

    /**
     * @return array<int,array<int,string>>
     */
    private function fillGrid(int $width, int $height): array
    {
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if (!isset($this->grid[$y][$x])) {
                    $this->grid[$y][$x] = ' ';
                }
            }
        }

        return $this->grid;
    }

    private function clearLine(int $line): void
    {
        if (!isset($this->grid[$line])) {
            return;
        }
        foreach ($this->grid[$line] as $i => $cell) {
            $this->grid[$line][$i] = ' ';
        }
    }
}
