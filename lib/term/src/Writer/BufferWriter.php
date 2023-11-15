<?php

declare(strict_types=1);

namespace PhpTui\Term\Writer;

use PhpTui\Term\Writer;

final class BufferWriter implements Writer
{
    private function __construct(private string $buffer)
    {
    }

    public static function new(): self
    {
        return new self('');
    }

    public function write(string $bytes): void
    {
        $this->buffer .= $bytes;
    }

    public function toString(): string
    {
        return $this->buffer;
    }
}
