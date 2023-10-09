<?php

namespace DTL\PhpTui\Model;

use Countable;
use RuntimeException;

final class BufferUpdates implements Countable
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
}
