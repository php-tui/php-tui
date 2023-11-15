<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\FloatPosition;

class MapPainter implements ShapePainter
{
    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        if (!$shape instanceof Map) {
            return;
        }

        foreach ($shape->mapResolution->data() as [$x, $y]) {
            if ($point = $painter->getPoint(FloatPosition::at($x, $y))) {
                $painter->paint($point, $shape->color);
            }
        }
    }
}
