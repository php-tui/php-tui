<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Text;

use Generator;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;

interface LineComposer
{
    /**
     * @return Generator<array{list<StyledGrapheme>, int, HorizontalAlignment}>
     */
    public function nextLine(): Generator;
}
