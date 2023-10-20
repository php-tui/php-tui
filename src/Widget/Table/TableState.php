<?php

namespace DTL\PhpTui\Widget\Table;

class TableState
{
    public function __construct(
        public int $offset,
        public ?int $selected,
    )
    {
    }
}
