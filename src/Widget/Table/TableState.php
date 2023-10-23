<?php

namespace PhpTui\Tui\Widget\Table;

class TableState
{
    public function __construct(
        public int $offset = 0,
        public ?int $selected = null,
    ) {
    }
}
