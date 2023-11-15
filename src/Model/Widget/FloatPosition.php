<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Widget;

use Closure;
use PhpTui\Tui\Model\AxisBounds;
use RuntimeException;
use Stringable;

final class FloatPosition implements Stringable
{
    public function __construct(public float $x, public float $y)
    {
    }

    public function __toString(): string
    {
        return sprintf('(%s,%s)', $this->x, $this->y);
    }

    public static function at(float $x, float $y): self
    {
        return new self($x, $y);
    }

    public function outOfBounds(AxisBounds $xBounds, AxisBounds $yBounds): bool
    {
        return (false === $xBounds->contains($this->x)) || (false === $yBounds->contains($this->y));
    }

    public function update(float $x, float $y): void
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Mutate the position with a closure which is passed the current X and Y coordinates.
     * @param Closure(float,float):array{float,float} $closure
     */
    public function change(Closure $closure): void
    {
        $new = $closure($this->x, $this->y);
        /** @phpstan-ignore-next-line runtime check */
        if (!is_array($new)) {
            throw new RuntimeException(sprintf('Change closure must return an array, got: %s', gettype($new)));
        }
        /** @phpstan-ignore-next-line runtime check */
        if (count($new) !== 2) {
            throw new RuntimeException(sprintf('Change closure must return a tuple of two elements ([$x, $y]), got %d', count($new)));
        }

        $this->x = $new[0];
        $this->y = $new[1];
    }
}
