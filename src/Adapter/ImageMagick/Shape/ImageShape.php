<?php

namespace PhpTui\Tui\Adapter\ImageMagick\Shape;

use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * Renders an image on the canvas.
 */
final class ImageShape implements Shape
{
    private function __construct(
        /**
         * Absolute path to the image
         */
        public readonly string $path,

        /**
         * Position to render at (bottom left)
         */
        public FloatPosition $position,
    ) {
    }

    public function position(FloatPosition $position): self
    {
        $this->position = $position;
        return $this;
    }
}
