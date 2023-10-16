<?php

namespace DTL\Cassowary;

use ArrayIterator;
use IteratorAggregate;
use RuntimeException;
use SplObjectStorage;
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
        /** @var SplObjectStorage<Variable,float> $found */
        $found = new SplObjectStorage();

        foreach ($this->changes as [$variable, $value]) {
            foreach ($targets as $i => $target) {
                if ($variable === $target) {
                    if (!$found->offsetExists($variable)) {
                        $found[$target] = $value;
                    }
                    continue 2;
                }
            }
        }

        return array_map(function (Variable $target) use ($found) {
            if (!$found->offsetExists($target)) {
                return 0.0;
            }
            return $found[$target];
        }, $targets);
    }


}
