<?php

namespace DTL\PhpTui\Model;

use Countable;

final class Buffer implements Countable
{
    /**
     * @param Cell[] $content
     */
    public function __construct(
        public readonly Area $area,
        public readonly array $content
    ) {
    }

    public static function filled(Area $area, Cell $cell): self
    {
    }

    public function count(): int
    {
        return count($this->content);
    }
}
