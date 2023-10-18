<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Color;

class Map implements Shape
{
    private function __construct(private MapResolution $mapResolution, private Color $color)
    {
    }

    public function draw(Painter $painter): void
    {
    }

    public function resolution(MapResolution $resolution): self
    {
        $this->resolution = $resolution;
        return $this;
    }

    public static function default(): self
    {
        return new self(MapResolution::Low, AnsiColor::Reset);
    }
}
