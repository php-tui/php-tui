<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Command;

use PhpTui\Tui\Example\Demo\Command;
use PhpTui\Tui\Example\Demo\Component;

class FocusCommand implements Command
{
    public function __construct(public readonly ?Component $component)
    {
    }
}
