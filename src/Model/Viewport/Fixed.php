<?php

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Viewport;

final class Fixed implements Viewport
{
    public function __construct(public readonly Area $area)
    {
    }

    public function size(Backend $backend): Area
    {
        return $this->area;
    }

    public function cursorPos(Backend $backend): Position
    {
        return new Position($this->area->left(), $this->area->right());
    }

    public function area(Backend $backend, int $offsetInPreviousViewport): Area
    {
        return $this->area;
    }
}
