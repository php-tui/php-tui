<?php

declare(strict_types=1);

namespace PhpTui\Tui\Text;

use Generator;
use PhpTui\Tui\Widget\HorizontalAlignment;

interface LineComposer
{
    /**
     * @return Generator<array{list<StyledGrapheme>, int, HorizontalAlignment}>
     */
    public function nextLine(): Generator;
}
