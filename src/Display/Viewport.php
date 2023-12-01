<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Backend;
use PhpTui\Tui\Position\Position;

interface Viewport
{
    public function size(Backend $backend): Area;

    public function cursorPos(Backend $backend): Position;

    public function area(Backend $backend, int $offsetInPreviousViewport): Area;

    public function clear(Backend $backend, Area $area): void;
}
