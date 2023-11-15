<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

use ArrayIterator;
use IteratorAggregate;
use PhpTui\Tui\Model\AxisBounds;
use Traversable;

/**
 * @implements IteratorAggregate<Label>
 */
class Labels implements IteratorAggregate
{
    /**
     * @param array<int,Label> $labels
     */
    public function __construct(private array $labels)
    {
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

    public function withinBounds(AxisBounds $xBounds, AxisBounds $yBounds): self
    {
        return new self(array_filter($this->labels, function (Label $label) use ($xBounds, $yBounds) {
            return $xBounds->contains($label->position->x) && $yBounds->contains($label->position->y);
        }));
    }
}
