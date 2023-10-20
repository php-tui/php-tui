<?php

namespace DTL\PhpTui\Widget\Table;

final class TableState
{
    public function __construct(
        public int $offset,
        public ?int $selected,
    ) {}
}
