<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Widget\FloatPosition;

class Line implements Shape
{
    public function __construct(
        public FloatPosition $point1,
        public FloatPosition $point2,
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
}
