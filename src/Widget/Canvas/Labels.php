<?php

namespace DTL\PhpTui\Widget\Canvas;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
/**
 * @implements IteratorAggregate<Label>
 */
class Labels implements IteratorAggregate
{
    /**
     * @param array<int,Label> $labels
     */
    public function __construct(private array $labels) {
    }

    public static function none(): self
    {
        return new self([]);
    }

    public function add(Label $label): void
    {
        $this->labels[] = $label;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->labels);
    }
}
