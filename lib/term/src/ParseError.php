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
            'Could not parse char "%s" in "%s"',
            $buffer[$offset],
            json_encode(implode('', $buffer))
        ));
    }
}
