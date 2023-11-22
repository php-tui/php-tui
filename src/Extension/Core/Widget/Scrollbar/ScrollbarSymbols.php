<?php

declare(strict_types=1);

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
            '▲',
            '▼',
        );
    }

    public static function doubleHorizontal(): self
    {
        return new self(
            LineSet::DOUBLE_HORIZONTAL,
            BlockSet::FULL,
            begin: '◄',
            end: '►',
        );
    }

    public static function vertical(): self
    {
        return new self(
            LineSet::VERTICAL,
            BlockSet::FULL,
            begin: '↑',
            end: '↓',
        );
    }

    public static function horizontal(): self
    {
        return new self(
            LineSet::HORIZONTAL,
            BlockSet::FULL,
            begin: '←',
            end: '→',
        );
    }
}
