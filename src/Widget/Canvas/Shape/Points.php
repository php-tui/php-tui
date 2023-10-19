<?php

namespace DTL\PhpTui\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
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
