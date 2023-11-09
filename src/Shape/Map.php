<?php

namespace PhpTui\Tui\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Renders a map of the world!
 */
class Map implements Shape
{
    public function __construct(
        /**
         * Resolution of the map (enum low or high)
         */
        public MapResolution $mapResolution,
        /**
         * Color of the map
         */
        public Color $color
    ) {
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
