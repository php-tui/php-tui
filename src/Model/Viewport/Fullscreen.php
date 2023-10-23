<?php

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Viewport;

final class Fullscreen implements Viewport
{
    public function computeArea(Backend $backend, Area $size, int $offsetInPreviousViewport): Area
    {
        return $size;
    }
}
