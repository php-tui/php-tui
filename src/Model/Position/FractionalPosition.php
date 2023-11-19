<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Position;

use RuntimeException;
use Stringable;

/**
 * Represents a position within the bounds of -1 and 1.
 */
final class FractionalPosition implements Stringable
{
    private function __construct(
        public readonly float $x,
        public readonly float $y
    ) {
        if ($x < -1 || $x > 1 || $y < -1 || $y > 1) {
            throw new RuntimeException(sprintf(
                'Fractional axis must be between 0 and 1 got [%f, %f]',
                $x,
                $y
            ));
        }
    }

    public function __toString(): string
    {
        return sprintf('[%.2f, %.2f]', $this->x, $this->y);
    }

    public static function at(float $x, float $y): self
    {
        return new self($x, $y);
    }

    public function rotate(float $radians): self
    {
        return new self(
            min(1, max(-1, (cos($radians) * $this->x - sin($radians) * $this->y))),
            min(1, max(-1, (sin($radians) * $this->x + cos($radians) * $this->y))),
        );
    }

    public function translate(self $by): self
    {
        return new self(min(1, $this->x + $by->x), min(1, $this->y + $by->y));
    }

    public function invert(): self
    {
        return new self(-$this->x, -$this->y);
    }
}
