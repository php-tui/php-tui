<?php

namespace PhpTui\Tui\Model\Widget;

use ArrayIterator;
use PhpTui\Tui\Model\Style;
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
        ], null);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->spans);
    }

    public function patchStyle(Style $style): self
    {
        foreach ($this->spans as $span) {
            $span->patchStyle($style);
        }
        return $this;
    }

    public static function fromSpan(Span $span): self
    {
        return new self([$span], HorizontalAlignment::Left);
    }
}
