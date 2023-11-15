<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface Writer
{
    public function write(string $bytes): void;
}
