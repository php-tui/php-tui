<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Display\Viewport;

use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Backend;
use PhpTui\Tui\Model\Display\ClearType;
use PhpTui\Tui\Model\Display\Viewport;
use PhpTui\Tui\Model\Position\Position;

/**
 * Viewport that occupies the entire screen.
 */
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

    public function clear(Backend $backend, Area $area): void
    {
        $backend->clearRegion(ClearType::ALL);
    }
}
