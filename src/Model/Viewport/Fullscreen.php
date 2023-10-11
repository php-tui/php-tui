<?php

namespace DTL\PhpTui\Model\Viewport;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\Viewport;

final class Fullscreen implements Viewport
{
    public function computeArea(Backend $backend, Area $size, int $offsetInPreviousViewport): Area
    {
        return $size;
    }
}
