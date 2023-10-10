<?php

namespace DTL\PhpTui\Model\Viewport;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\Viewport;

class Fixed implements Viewport
{
    public function __construct(public readonly Area $area)
    {
    }

    public function computeArea(Backend $backend, Area $area, int $offsetInPreviousViewport): Area
    {
        return $area;
    }
}
