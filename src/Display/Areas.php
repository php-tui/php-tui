<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use ArrayIterator;
use IteratorAggregate;
use PhpTui\Tui\Display\Area;
use RuntimeException;
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

    public function get(int $offset): Area
    {
        if (!isset($this->areas[$offset])) {
            throw new RuntimeException(sprintf(
                'Area at offset %d not set, known offsets: %s',
                $offset,
                implode(', ', array_keys($this->areas))
            ));
        }

        return $this->areas[$offset];
    }

    public function has(int $offset): bool
    {
        return isset($this->areas[$offset]);
    }

}
