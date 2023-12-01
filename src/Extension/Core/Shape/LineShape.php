<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FloatPosition;

/**
 * Draw a straight line from one point to another.
 */
final class LineShape implements Shape
{
    public function __construct(
        /**
         * Draw from this point
         */
        public FloatPosition $point1,
        /**
         * Draw to this point
         */
        public FloatPosition $point2,
        /**
         * Color of the line
         */
        public Color $color
    ) {
    }

    public static function fromScalars(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
    ): self {
        return new self(FloatPosition::at($x1, $y1), FloatPosition::at($x2, $y2), AnsiColor::Reset);
    }

    public function color(Color $color): self
    {
        $this->color = $color;

        return $this;
    }

}
