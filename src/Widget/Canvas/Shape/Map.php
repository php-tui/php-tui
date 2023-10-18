<?php

namespace DTL\PhpTui\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Widget\FloatPosition;
use DTL\PhpTui\Widget\Canvas\Painter;
use DTL\PhpTui\Widget\Canvas\Shape;

class Map implements Shape
{
    private function __construct(private MapResolution $mapResolution, private Color $color)
    {
    }

    public function draw(Painter $painter): void
    {
        foreach ($this->mapResolution->data() as [$x, $y]) {
            if ($point = $painter->getPoint(FloatPosition::at($x, $y))) {
                $painter->paint($point, $this->color);
            }
        }
    }

    public function resolution(MapResolution $resolution): self
    {
        $this->mapResolution = $resolution;
        return $this;
    }

    public static function default(): self
    {
        return new self(MapResolution::Low, AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}
