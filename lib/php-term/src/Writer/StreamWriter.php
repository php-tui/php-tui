<?php

namespace DTL\PhpTerm\Writer;

use DTL\PhpTerm\Writer;

class StreamWriter implements Writer
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
