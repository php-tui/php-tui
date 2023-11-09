<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Draws a circle at with the specified radius and color
 */
final class Circle implements Shape
{
    public function __construct(
        /**
         * Position of the circle
         */
        public FloatPosition $position,
        /**
         * Radius of the circle
         */
        public float $radius,
        /**
         * Color of the circle
         */
        public Color $color,
    ) {
    }

    public function draw(Painter $painter): void
    {
        foreach (range(0, 360) as $degree) {
            $radians = deg2rad($degree);
            $circleX = $this->radius * cos($radians) + $this->position->x;
            $circleY = $this->radius * sin($radians) + $this->position->y;
            if ($point = $painter->getPoint(FloatPosition::at($circleX, $circleY))) {
                $painter->paint($point, $this->color);
            }
        }
    }

    public static function fromScalars(float $x, float $y, float $radius): self
    {
        return new self(FloatPosition::at($x, $y), $radius, AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}
