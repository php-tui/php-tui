<?php

namespace DTL\Cassowary;

use ArrayIterator;
use IteratorAggregate;
use RuntimeException;
use Traversable;

/**
 * @implements IteratorAggregate<array{Variable,float}>
 */
class Changes implements IteratorAggregate
{
    /**
     * @param list<array{Variable,float}> $changes
     */
    public function __construct(private array $changes)
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->changes);
    }

    public function getValue(Variable $target): float
    {
        foreach ($this->changes as $pair) {
            [$variable, $value] = $pair;
            if ($variable === $target) {
                return $value;
            }
        }

        throw new RuntimeException(sprintf(
            'Could not find value "%s"',
            $target->__toString()
        ));
    }

    /**
     * This is inefficient, use only for testing
     * @return float[]
     */
    public function getValues(Variable ...$targets): array
    {
        $found = [];

        foreach ($this->changes as [$variable, $value]) {
            foreach ($targets as $i => $target) {
                if ($variable === $target) {
                    $found[] = $value;
                    unset($targets[$i]);
                    continue 2;
                }
            }
            $found[] = 0.0;
        }

        return $found;
    }


}
