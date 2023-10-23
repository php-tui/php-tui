<?php

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Exception\TodoException;
use PhpTui\Tui\Model\Viewport;

final class Inline implements Viewport
{
    public function __construct(public readonly int $height)
    {
    }

    public function computeArea(Backend $backend, Area $area, int $offsetInPreviousViewport): Area
    {
        throw new TodoException('Inline views');
    }
}
