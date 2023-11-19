<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick\Shape;

use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Position\FloatPosition;
use RuntimeException;

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

    public static function fromPath(string $imagePath): self
    {
        if (!file_exists($imagePath)) {
            throw new RuntimeException(sprintf(
                'Imagefile "%s" does not exist',
                $imagePath
            ));
        }

        return new self($imagePath, new FloatPosition(0, 0));
    }
}
