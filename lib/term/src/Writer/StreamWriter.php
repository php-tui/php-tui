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
        for ($written = 0; $written < strlen($bytes); $written += $fwrite) {
            $fwrite = fwrite($this->stream, substr($bytes, $written));
            if ($fwrite !== false) {
                continue;
            }

            return;
        }
    }


    public function flush(): void
    {
        fflush($this->stream);
    }
}
