<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Position;

class LinePainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof LineShape) {
            return;
        }

        $point1 = $painter->getPoint($shape->point1);
        $point2 = $painter->getPoint($shape->point2);

        if (null === $point1) {
            return;
        }

        if (null === $point2) {
            return;
        }

        [$diffX, $xRange] = self::resolveDiffAndRange($point1->x, $point2->x);
        [$diffY, $yRange] = self::resolveDiffAndRange($point1->y, $point2->y);

        if ($diffX === 0) {
            foreach ($yRange as $y) {
                $painter->paint($point1->withY($y), $shape->color);
            }

            return;
        }

        if ($diffY === 0) {
            foreach ($xRange as $x) {
                $painter->paint($point1->withX($x), $shape->color);
            }

            return;
        }

        if ($diffY < $diffX) {
            if ($point1->x > $point2->x) {
                $this->drawLineLow($painter, $shape, $point2, $point1);

                return;
            }
            $this->drawLineLow($painter, $shape, $point1, $point2);

            return;
        }
        if ($point1->y > $point2->y) {
            $this->drawLineHigh($painter, $shape, $point2, $point1);

            return;
        }

        $this->drawLineHigh($painter, $shape, $point1, $point2);
    }

    /**
     * @return array{int, int[]}
     */
    private static function resolveDiffAndRange(int $start, int $end): array
    {
        if ($end >= $start) {
            return [$end - $start, range($start, $end)];
        }

        return [$start - $end, range($end, $start)];
    }

    private function drawLineLow(Painter $painter, LineShape $line, Position $point1, Position $point2): void
    {
        $diffX = $point2->x - $point1->x;
        $diffY = abs($point2->y - $point1->y);
        $diff = 2 * $diffY - $diffX;
        $y = $point1->y;
        foreach (range($point1->x, $point2->x) as $x) {
            $painter->paint(Position::at($x, $y), $line->color);
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

    private function drawLineHigh(Painter $painter, LineShape $line, Position $point1, Position $point2): void
    {
        $diffX = abs($point2->x - $point1->x);
        $diffY = $point2->y - $point1->y;
        $diff = 2 * $diffX - $diffY;
        $x = $point1->x;
        foreach (range($point1->y, $point2->y) as $y) {
            $painter->paint(Position::at($x, $y), $line->color);
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
