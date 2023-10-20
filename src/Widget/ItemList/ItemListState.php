<?php

namespace DTL\PhpTui\Widget\ItemList;

final class ItemListState
{
    public function __construct(
        public int $offset = 0,
        public ?int $selected = null,
    ) {
    }
}
