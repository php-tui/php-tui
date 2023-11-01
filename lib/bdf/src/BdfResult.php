<?php

namespace PhpTui\BDF;

/**
 * @template TValue
 */
final class BdfResult
{
    /**
     * @param TValue $value
     */
    public function __construct(
        public mixed $value,
        public BdfByteStream $rest
    )
    {
    }
}
