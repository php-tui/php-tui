<?php

namespace PhpTui\Tui\Model;

interface Viewport
{
    public function computeArea(Backend $backend, Area $area, int $offsetInPreviousViewport): Area;
}
