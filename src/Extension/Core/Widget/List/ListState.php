<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\List;

final class ListState
{
    public function __construct(
        public int $offset = 0,
        public ?int $selected = null,
    ) {
    }
}
