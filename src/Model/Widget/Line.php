<?php

namespace DTL\PhpTui\Model\Widget;

final class Line
{
    /**
     * @param Span[] $spans
     */
    public function __construct(
        public readonly array $spans,
        public readonly HorizontalAlignment $alignment
    )
    {
    }

    public static function fromString(string $string): self
    {
        return new self([
            Span::fromString($string)
        ], HorizontalAlignment::Left);
    }
}
