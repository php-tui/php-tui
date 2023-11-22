<?php

namespace PhpTui\Tui\Extension\Core\Widget\Scrollbar;

use PhpTui\Tui\Model\Symbol\BlockSet;
use PhpTui\Tui\Model\Symbol\LineSet;

final class ScrollbarSymbols
{
    public function __construct(
        public string $track,
        public string $thumb,
        public string $begin,
        public string $end,
    ) {
    }

    public static function doubleVertical(): self
    {
        return new self(
            LineSet::DOUBLE_VERTICAL,
            BlockSet::FULL,
            "▲",
            "▼",
        );
    }
}
