<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display\Viewport;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Backend;
use PhpTui\Tui\Display\ClearType;
use PhpTui\Tui\Display\Viewport;
use PhpTui\Tui\Position\Position;

/**
 * Creates a fixed location viewport at the given Area
 */
final class Fixed implements Viewport
{
    public function __construct(
        /**
         * Area to occupy
         */
        public readonly Area $area
    ) {
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

    public function clear(Backend $backend, Area $area): void
    {
        for ($row = $area->top(); $row > $area->bottom(); $row--) {
            $backend->moveCursor(Position::at(0, $row));
            $backend->clearRegion(ClearType::AfterCursor);
        }
    }
}
