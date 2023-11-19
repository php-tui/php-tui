<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

interface Parseable
{
    public static function parse(string $string): self;
}
