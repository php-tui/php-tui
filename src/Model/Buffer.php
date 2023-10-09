<?php

namespace DTL\PhpTui\Model;

use Countable;

final class Buffer implements Countable
{
    /**
     * @param Cell[] $content
     */
    public function __construct(
        private readonly Area $area,
        private readonly array $content
    ) {
    }

    public static function empty(Area $area): self
    {
        return new self($area, array_fill(0, $area->area(), Cell::empty()));
    }

    public static function filled(Area $area, Cell $cell): self
    {
        return new self($area, array_fill(0, $area->area(), $cell));
    }

    /**
     * @return Cell[]
     */
    public function content(): array
    {
        return $this->content;
    }

    public function count(): int
    {
        return count($this->content);
    }
}
