<?php

namespace DTL\PhpTui\Model\Backend;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\BufferUpdates;

class DummyBackend implements Backend
{
    /**
     * @var array<int,array<int,string>>
     */
    private array $grid;

    public function __construct(private int $width, private int $height)
    {
        $grid = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $grid[$y][$x] = ' ';
            }
        }
        $this->grid = $grid;
    }

    public function size(): Area
    {
        return Area::fromPrimatives(0, 0, $this->width, $this->height);
    }

    public function draw(BufferUpdates $updates): void
    {
        foreach ($updates as $update) {
            $this->grid[$update->position->y][$update->position->x] = $update->char;
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
}
