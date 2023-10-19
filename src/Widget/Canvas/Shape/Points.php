<?php

namespace DTL\PhpTui\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Widget\FloatPosition;
use DTL\PhpTui\Widget\Canvas\Painter;
use DTL\PhpTui\Widget\Canvas\Shape;

class Points implements Shape
{
    /**
     * @param array<int,array{float,float}> $coords
     */
    public function __construct(public array $coords, public AnsiColor $color)
    {
    }

    public function draw(Painter $painter): void
    {
        foreach ($this->coords as [$x, $y]) {
            if (!$point = $painter->getPoint(FloatPosition::at($x, $y))) {
                continue;
            }
            $painter->paint($point, $this->color);
        }
    }

    /**
     * @param list<array{float,float}> $coords
     */
    public static function new(array $coords, AnsiColor $color): self
    {
        return new self(
            $coords,
            $color
        );
    }
}
