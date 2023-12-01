<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;
use PhpTui\Tui\Model\Widget\Widget;
use RuntimeException;

/**
 * Render an image on a canvas matching the dimensions of the image.
 */
final class ImageWidget implements Widget
{
    public function __construct(
        /**
         * Absolute path to the image
         */
        public string $path,
        /**
         * Canvas marker to use, defaults to Marker::HalfBlock
         */
        public ?Marker $marker = null
    ) {
    }

    public static function fromPath(string $imagePath): self
    {
        if (!file_exists($imagePath)) {
            throw new RuntimeException(sprintf(
                'Imagefile "%s" does not exist',
                $imagePath
            ));
        }

        return new self($imagePath);
    }
}
