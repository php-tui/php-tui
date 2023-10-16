<?php

namespace DTL\PhpTui\Model\Widget;

use ArrayIterator;
use DTL\PhpTui\Model\Style;
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
        public readonly ?HorizontalAlignment $alignment = null
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

    /**
     * @param Span[] $spans
     */
    public static function fromSpans(array $spans): self
    {
        return new self($spans);
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

    public function patchStyle(Style $style): void
    {
        foreach ($this->spans as $span) {
            $span->patchStyle($style);
        }
    }
}
