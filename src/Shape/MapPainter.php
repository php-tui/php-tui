<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

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
