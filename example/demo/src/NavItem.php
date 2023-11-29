<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo;

final class NavItem
{
    public function __construct(public string $shortcut, public string $label)
    {
    }
}
