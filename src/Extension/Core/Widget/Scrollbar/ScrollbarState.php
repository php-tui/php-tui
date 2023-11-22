<?php

namespace PhpTui\Tui\Extension\Core\Widget\Scrollbar;

class ScrollbarState
{
    public function __construct(
        public int $contentLength = 0,
        public int $position = 0,
        public int $viewportContentLength = 0,
    ) {}
}
