<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Table;

final class TableState
{
    public function __construct(
        public int $offset = 0,
        public ?int $selected = null,
    ) {
    }
}
