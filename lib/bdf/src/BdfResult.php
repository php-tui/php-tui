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
        public readonly bool $ok,
        public readonly mixed $value,
        public readonly BdfTokenStream $rest
    ) {
    }

    /**
     * @template TParam
     * @param TParam $value
     * @return BdfResult<TParam>
     */
    public static function ok(mixed $value, BdfTokenStream $rest): self
    {
        return new self(true, $value, $rest);
    }

    /**
     * @template TParam
     * @param TParam $value
     * @return BdfResult<TParam>
     */
    public static function failure(mixed $value, BdfTokenStream $rest): self
    {
        return new self(false, $value, $rest);
    }

    public function isOk(): bool
    {
        return $this->ok === true;
    }
}
