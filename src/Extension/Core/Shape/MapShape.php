<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\Color;

/**
 * Renders a map of the world!
 */
final class MapShape implements Shape
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
