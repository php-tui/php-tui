<?php

namespace PhpTui\Tui\Model;

interface Viewport
{
    public function size(Backend $backend): Area;

    public function cursorPos(Backend $backend): Position;

    public function area(Backend $backend, Position $cursorPos, int $offsetInPreviousViewport): Area;
}
