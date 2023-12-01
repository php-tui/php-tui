<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use PhpTui\Tui\Position\Position;

final class BufferUpdate
{
    public function __construct(public Position $position, public Cell $cell)
    {
    }
}
