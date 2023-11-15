<?php

namespace PhpTui\Tui\Extension\ImageMagick\Widget;

use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;

class ImageWidget implements Widget
{
    public function __construct(public string $path, public ?Marker $marker = null) {
    }
}
