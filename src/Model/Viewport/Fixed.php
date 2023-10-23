<?php

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Viewport;

final class Fixed implements Viewport
{
    public function __construct(public readonly Area $area)
    {
    }

    public function computeArea(Backend $backend, Area $area, int $offsetInPreviousViewport): Area
    {
        return $area;
    }
}
