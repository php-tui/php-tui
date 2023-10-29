<?php

namespace PhpTui\Term;

use RuntimeException;

class ParseError extends RuntimeException
{
    /**
     * @param string[] $buffer
     */
    public static function couldNotParseOffset(array $buffer, int $offset): self
    {
        return new self(sprintf(
            'Could not parse char "%s" (offset %d) in "%s"',
            $buffer[$offset],
            $offset,
            json_encode(implode('', $buffer))
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
