<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Math;

final class VectorUtil
{
    /**
     * Return the maximum value of the given vector of numbers.
     *
     * - If the vector is empty return null
     * - If the vector has one element, return it
     * - Otherwise return the max
     *
     * @template T of list<number>
     * @param T $vector
     * @return (T is non-empty-array ? number : null)
     */
    public static function max(array $vector): int|float|null
    {
        if ([] === $vector) {
            return null;
        }
        if (count($vector) === 1) {
            return $vector[array_key_first($vector)];
        }

        return max(...$vector);
    }

    /**
     * Return the minimum value of the given vector of numbers.
     *
     * - If the vector is empty return null
     * - If the vector has one element, return it
     * - Otherwise return the max
     *
     * @template T of list<number>
     * @param T $vector
     * @return (T is non-empty-array ? number : null)
     */
    public static function min(array $vector): int|float|null
    {
        if ([] === $vector) {
            return null;
        }
        if (count($vector) === 1) {
            return $vector[array_key_first($vector)];
        }

        return min(...$vector);
    }
}
