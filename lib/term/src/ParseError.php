<?php

declare(strict_types=1);

namespace PhpTui\Term;

use RuntimeException;

class ParseError extends RuntimeException
{
    /**
     * @param string[] $buffer
     */
    public static function couldNotParseOffset(array $buffer, int $offset, ?string $message = null): self
    {
        return new self(sprintf(
            'Could not parse char "%s" (offset %d) in "%s"%s',
            $buffer[$offset],
            $offset,
            json_encode(implode('', $buffer)),
            $message ? ': ' . $message : '',
        ));
    }

    /**
     * @param string[] $buffer
     */
    public static function couldNotParseBuffer(array $buffer): self
    {
        return new self(sprintf(
            'Could not parse graphics mode: %s',
            json_encode(implode('', $buffer))
        ));
    }
}
