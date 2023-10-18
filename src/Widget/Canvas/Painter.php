<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Widget\FloatPosition;

class Painter
{
    public function __construct(public CanvasContext $context, public Resolution $resolution)
    {
    }

    public function getPoint(FloatPosition $floatPosition): ?Position
    {
        if ($floatPosition->outOfBounds($this->context->xBounds, $this->context->yBounds)) {
            return null;
        }
        $width = $this->context->xBounds->length();
        $height = $this->context->yBounds->length();
        if ($width === 0 || $height === 0) {
            return null;
        }
        $x = (($floatPosition->x - $this->context->xBounds->min) * ($this->resolution->x - 1.0) / $width);
        $y = (($this->context->yBounds->max - $floatPosition->y) * ($this->resolution->y - 1.0) / $height);

        return Position::at(intval($x), intval($y));
    }

    public function paint(Position $position, Color $color): void
    {
        $this->context->grid->paint($position, $color);
    }

}
