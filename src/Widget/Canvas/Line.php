<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Widget\FloatPosition;

class Line implements Shape
{
    public function __construct(
        public FloatPosition $xPosition,
        public FloatPosition $yPosition,
        public Color $color
    ) {
    }

    public static function fromPrimitives(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
        Color $color
    ): self {
        return new self(FloatPosition::at($x1, $y1), FloatPosition::at($x2, $y2), $color);
    }
}
