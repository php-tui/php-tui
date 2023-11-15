<?php

declare(strict_types=1);

namespace PhpTui\Term\Reader;

use PhpTui\Term\Reader;

final class StreamReader implements Reader
{
    /**
     * @param resource $stream
     */
    private function __construct(private $stream)
    {
    }

    public static function tty(): self
    {
        // TODO: open `/dev/tty` is STDIN is not a TTY
        $resource = STDIN;
        stream_set_blocking($resource, false);

        return new self(STDIN);
    }

    public function read(): ?string
    {
        $bytes = fgets($this->stream);
        if (false === $bytes) {
            return null;
        }

        return $bytes;
    }
}
