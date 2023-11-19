<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Widget;

use ArrayIterator;
use IteratorAggregate;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Style\StyleableTrait;
use PhpTui\Tui\Model\Styleable;
use Stringable;
use Traversable;

/**
 * @implements IteratorAggregate<Span>
 */
final class Line implements IteratorAggregate, Stringable, Styleable
{
    use StyleableTrait;

    /**
     * @param Span[] $spans
     */
    public function __construct(
        public readonly array $spans,
        public ?HorizontalAlignment $alignment = null
    ) {
    }

    public function __toString(): string
    {
        return implode('', array_map(fn (Span $span) => $span->__toString(), $this->spans));
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

    public static function parse(string $string): self
    {
        return self::fromSpans(
            SpanParser::new()->parse($string)
        );
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->spans);
    }

    /**
     * Patches the style of each Span in an existing Line, adding modifiers from the given style.
     */
    public function patchStyle(Style $style): self
    {
        foreach ($this->spans as $span) {
            $span->patchStyle($style);
        }

        return $this;
    }

    /**
     * Sets the target alignment for this line of text.
     */
    public function alignment(HorizontalAlignment $alignment): self
    {
        $this->alignment = $alignment;

        return $this;
    }

    public static function fromSpan(Span $span): self
    {
        return new self([$span], HorizontalAlignment::Left);
    }
}
