<?php

namespace DTL\PhpTui\Widget\ItemList;

final class TableState
{
    public function __construct(
        public int $offset,
        public ?int $selected,
    ) {
    }
}
