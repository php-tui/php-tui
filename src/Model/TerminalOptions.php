<?php

namespace PhpTui\Tui\Model;

final class TerminalOptions
{
    public function __construct(public readonly Viewport $viewport)
    {
    }
}
