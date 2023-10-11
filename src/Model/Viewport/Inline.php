<?php

namespace DTL\PhpTui\Model\Viewport;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\Exception\TodoException;
use DTL\PhpTui\Model\Viewport;

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
