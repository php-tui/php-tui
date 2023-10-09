<?php

namespace DTL\PhpTui\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use Traversable;
/**
 * @implements IteratorAggregate<BufferUpdate>
 */
final class BufferUpdates implements Countable, IteratorAggregate
{
    /**
     * @param BufferUpdate[] $updates
     */
    public function __construct(private array $updates) {
    }

    public function count(): int
    {
        return count($this->updates);
    }

    public function at(int $index): BufferUpdate
    {
        if (!isset($this->updates[$index])) {
            throw new RuntimeException(sprintf(
                'No buffer update at index %d (%d indexes)',
                $index, count($this->updates)
            ));
        }
        return $this->updates[$index];
    }

    public function last(): BufferUpdate
    {
        if (count($this->updates) === 0) {
            throw new RuntimeException(
                'Cannot get last update because there are no updates'
            );
        }
        return $this->updates[count($this->updates) - 1];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->updates);
    }
}
