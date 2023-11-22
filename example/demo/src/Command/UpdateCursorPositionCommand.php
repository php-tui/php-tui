<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Command;

use PhpTui\Tui\Example\Demo\Command;
use PhpTui\Tui\Model\Position\Position;

class UpdateCursorPositionCommand implements Command
{
    public function __construct(public readonly Position $position)
    {
    }
}
