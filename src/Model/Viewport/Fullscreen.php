<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Viewport;

final class Fullscreen implements Viewport
{
    public function size(Backend $backend): Area
    {
        return $backend->size();
    }

    public function cursorPos(Backend $backend): Position
    {
        return new Position(0, 0);
    }

    public function area(Backend $backend, int $offsetInPreviousViewport): Area
    {
        return $this->size($backend);
    }
}
