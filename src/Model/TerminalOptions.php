<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

final class TerminalOptions
{
    public function __construct(public readonly Viewport $viewport)
    {
    }
}
