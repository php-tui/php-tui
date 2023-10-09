<?php

namespace DTL\PhpTui\Model;

final class BufferUpdates
{
    /**
     * @param BufferUpdate[] $updates
     */
    public function __construct(private array $updates) {
    }
}
