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
    private function __construct(
        public bool $ok,
        public mixed $value,
        public BdfByteStream $rest
    )
    {
    }

    /**
     * @template TParam
     * @param TParam $value
     * @return BdfResult<TParam>
     */
    public static function ok(mixed $value, BdfByteStream $rest): self
    {
        return new self(true, $value, $rest);
    }

    /**
     * @template TParam
     * @param TParam $value
     * @return BdfResult<TParam>
     */
    public static function failure(mixed $value, BdfByteStream $rest): self
    {
        return new self(false, $value, $rest);
    }

    public function isOk(): bool
    {
        return $this->ok === true;
    }
}
