<?php

namespace DTL\PhpTui\Widget\Table;

class TableState
{
    public function __construct(
        public int $offset = 0,
        public ?int $selected = null,
    ) {
    }
}
