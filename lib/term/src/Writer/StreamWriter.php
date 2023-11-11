<?php

namespace PhpTui\Term\Writer;

use PhpTui\Term\Writer;

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
        // fwrite does not always write the entire stream to STDOUT keep
        // writing until it's done
        for ($written = 0; $written < strlen($bytes); $written += $fwritten) {
            $fwritten = fwrite($this->stream, substr($bytes, $written));
            if ($fwritten !== false) {
                continue;
            }

            return;
        }
    }
}
