<?php

namespace DTL\PhpTui\Model;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
/**
 * @implements IteratorAggregate<Area>
 */
final class Areas implements IteratorAggregate
{
    /**
     * @param Area[] $areas
     */
    public function __construct(private array $areas)
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->areas);
    }
    /**
     * @return Area[]
     */
    public function toArray(): array
    {
        return $this->areas;
    }

}
