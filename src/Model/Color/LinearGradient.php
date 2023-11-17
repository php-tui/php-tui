<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Color;

use PhpTui\Tui\Model\Color;
use RuntimeException;

final class LinearGradient implements Color
{
    /**
     * @param non-empty-list<array{float,RgbColor}> $stops
     */
    private function __construct(private array $stops)
    {
    } 

    public static function from(RgbColor $color): self
    {
        return new self([[0, $color]]);
    }

    public function debugName(): string
    {
        return sprintf('Gradient()');
    }

    public function addStop(float $position, RgbColor $color): self
    {
        if ($position >= 1 || $position < 0) {
            throw new RuntimeException(sprintf(
                'Stop must be a float between 0 and 1, got %f',
                $position
            ));
        }

        $stops = $this->stops;
        $stops[] = [$position, $color];

        return new self($stops);
    }

    public function at(float $target): RgbColor
    {
        if ($target >= 1 || $target < 0) {
            throw new RuntimeException(sprintf(
                'Target position must be a float between 0 and 1, got %f',
                $target
            ));
        }

        // determine last stop
        $stops = $this->stops;
        usort($stops, fn (array $s1, array $s2) => $s1[0] <=> $s2[0]);
        [$lastPosition, $lastStop] = array_shift($stops);
        $nextStop = null;
        $nextPosition = null;
        foreach ($stops as [$position, $stop]) {
            if ($position > $target) {
                $nextStop = $stop;
                $nextPosition = $position;
                break;
            }
            $lastStop = $stop;
            $lastPosition = $lastPosition;
        }

        if ($nextStop === null || $nextPosition === null) {
            return $lastStop;
        }

        $diff = abs($lastPosition - $nextPosition);
        $target = $target * (1 / $diff);

        return RgbColor::fromRgb(
            $this->calculate($lastStop->r, $nextStop->r, $target),
            $this->calculate($lastStop->g, $nextStop->g, $target),
            $this->calculate($lastStop->b, $nextStop->b, $target),
        );
    }

    private function calculate(float $last, float $next, float $target): int
    {
        if ($last < $next) {
            return intval($last + (abs($next - $last) * $target));
        }

        return intval($next + (abs($last - $next) * $target));
        return 0;
    }
}
