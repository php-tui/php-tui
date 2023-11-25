<?php

declare(strict_types=1);

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
        $length = strlen($bytes);
        $written = 0;
        // fwrite does not always write the entire stream to STDOUT keep
        // writing until it's done
        while ($written < $length) {
            $fwritten = fwrite($this->stream, substr($bytes, $written));
            if ($fwritten === false) {
                return;
            }
            $written += $fwritten;
        }
    }
}
