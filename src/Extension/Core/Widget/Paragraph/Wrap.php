<?php

namespace PhpTui\Tui\Extension\Core\Widget\Paragraph;

final class Wrap
{
    private function __construct(public bool $trim)
    {
    }

    public static function trimmed(): self
    {
        return new self(trim: true);
    }
}
