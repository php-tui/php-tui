<?php

namespace DTL\PhpTerm\Writer;

use DTL\PhpTerm\TermWriter;

final class BufferWriter implements TermWriter
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
