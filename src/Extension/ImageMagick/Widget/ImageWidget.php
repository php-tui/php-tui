<?php

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;

/**
 * Render an image on a canvas matching the dimensions of the image.
 */
class ImageWidget implements Widget
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
}
