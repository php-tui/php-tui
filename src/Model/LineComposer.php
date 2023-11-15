<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use Generator;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\StyledGrapheme;

interface LineComposer
{
    /**
     * @return Generator<array{list<StyledGrapheme>, int, HorizontalAlignment}>
     */
    public function nextLine(): Generator;
}
