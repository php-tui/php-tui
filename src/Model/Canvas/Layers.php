<?php

namespace PhpTui\Tui\Model\Canvas;

use ArrayIterator;
use IteratorAggregate;
use PhpTui\Tui\Model\Canvas\Layer;
use Traversable;

/**
 * @implements IteratorAggregate<Layer>
 */
final class Layers implements IteratorAggregate
{
    /**
     * @param Layer[] $layers
     */
    public function __construct(public array $layers)
    {
    }

    public static function none(): self
    {
        return new self([]);
    }

    public function add(Layer $layer): void
    {
        $this->layers[] = $layer;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->layers);
    }
}
