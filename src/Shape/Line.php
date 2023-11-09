<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Draw a straight line from one point to another.
 */
class Line implements Shape
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

    public function draw(Painter $painter): void
    {
        $point1 = $painter->getPoint($this->point1);
        $point2 = $painter->getPoint($this->point2);
        if (null === $point1) {
            return;
        }
        if (null === $point2) {
            return;
        }
        [$diffX, $xRange] = $this->resolveDiffAndRange($point1->x, $point2->x);
        [$diffY, $yRange] = $this->resolveDiffAndRange($point1->y, $point2->y);

        if ($diffX === 0) {
            foreach ($yRange as $y) {
                $painter->paint($point1->withY($y), $this->color);
            }
            return;
        }

        if ($diffY === 0) {
            foreach ($xRange as $x) {
                $painter->paint($point1->withX($x), $this->color);
            }
            return;
        }

        if ($diffY < $diffX) {
            if ($point1->x > $point2->x) {
                $this->drawLineLow($painter, $point2, $point1);
                return;
            }
            $this->drawLineLow($painter, $point1, $point2);
            return;
        }
        if ($point1->y > $point2->y) {
            $this->drawLineHigh($painter, $point2, $point1);
            return;
        }

        $this->drawLineHigh($painter, $point1, $point2);
    }

    /**
     * @return array{int, int[]}
     */
    private function resolveDiffAndRange(int $start, int $end): array
    {
        if ($end >= $start) {
            return [$end - $start, range($start, $end)];
        }
        return [$start - $end, range($end, $start)];
    }

    private function drawLineLow(Painter $painter, Position $point1, Position $point2): void
    {
        $diffX = $point2->x - $point1->x;
        $diffY = abs($point2->y - $point1->y);
        $diff = 2 * $diffY - $diffX;
        $y = $point1->y;
        foreach (range($point1->x, $point2->x) as $x) {
            $painter->paint(Position::at($x, $y), $this->color);
            if ($diff > 0) {
                if ($point1->y > $point2->y) {
                    $y -= 1;
                } else {
                    $y += 1;
                }
                $diff -= 2 * $diffX;
            }
            $diff += 2 * $diffY;
        }
    }

    private function drawLineHigh(Painter $painter, Position $point1, Position $point2): void
    {
        $diffX = abs($point2->x - $point1->x);
        $diffY = $point2->y - $point1->y;
        $diff = 2 * $diffX - $diffY;
        $x = $point1->x;
        foreach (range($point1->y, $point2->y) as $y) {
            $painter->paint(Position::at($x, $y), $this->color);
            if ($diff > 0) {
                if ($point1->x > $point2->x) {
                    $x -= 1;
                } else {
                    $x += 1;
                }
                $diff -= 2 * $diffY;
            }
            $diff += 2 * $diffX;
        }
    }
}
