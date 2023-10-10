<?php

namespace DTL\PhpTui\Model\Widget;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<Span>
 */
final class Line implements IteratorAggregate
{
    /**
     * @param Span[] $spans
     */
    public function __construct(
        public readonly array $spans,
        public readonly HorizontalAlignment $alignment
    ) {
    }

    public function width(): int
    {
        return array_sum(
            array_map(
                fn (Span $span) => $span->width(),
                $this->spans
            )
        );
    }

    public static function fromString(string $string): self
    {
        return new self([
            Span::fromString($string)
        ], HorizontalAlignment::Left);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->spans);
    }
}
