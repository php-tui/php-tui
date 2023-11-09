<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * Renders a map of the world!
 */
class Map implements Shape
{
    public function __construct(
        /**
         * Resolution of the map (enum low or high)
         */
        private MapResolution $mapResolution,
        /**
         * Color of the map
         */
        private Color $color
    ) {
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
