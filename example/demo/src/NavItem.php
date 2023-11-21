<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo;

class NavItem
{
    public function __construct(public string $shortcut, public string $label)
    {
    }
}
