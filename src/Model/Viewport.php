<?php

namespace DTL\PhpTui\Model;

interface Viewport
{
    public function computeArea(Backend $backend, Area $area, int $offsetInPreviousViewport): Area;
}
