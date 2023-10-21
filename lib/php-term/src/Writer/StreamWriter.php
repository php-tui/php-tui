<?php

namespace DTL\PhpTerm\Writer;

use DTL\PhpTerm\TermWriter;

class StreamWriter implements TermWriter
{
    /**
     * @param resource $stream
     */
    private function __construct(private $stream)
    {
    }

    public static function stdout(): self
    {
        return new self(STDOUT);
    }

    public function write(string $bytes): void
    {
        fwrite($this->stream, $bytes);
    }
}
