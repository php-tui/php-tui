<?php

namespace PhpTui\Tui\Model\Backend;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\BufferUpdates;
use PhpTui\Tui\Model\ClearType;

final class DummyBackend implements Backend
{
    /**
     * @var array<int,array<int,string>>
     */
    private array $grid = [];

    private ?string $flushed = null;

    public function __construct(private int $width, private int $height)
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
        return implode("\n", array_map(function (array $cells) {
            return implode('', $cells);
        }, $this->grid));
    }

    public static function fromDimensions(int $width, int $height): self
    {

        return new self($width, $height);
    }

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
}
