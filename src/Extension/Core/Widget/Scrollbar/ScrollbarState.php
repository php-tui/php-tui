<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Scrollbar;

final class ScrollbarState
{
    public function __construct(
        public int $contentLength = 0,
        public int $position = 0,
        public int $viewportContentLength = 0,
    ) {
    }
}
