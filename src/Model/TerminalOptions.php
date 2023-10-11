<?php

namespace DTL\PhpTui\Model;

final class TerminalOptions
{
    public function __construct(public readonly Viewport $viewport)
    {
    }
}
