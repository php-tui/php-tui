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
        foreach ($stops as [$position, $stop]) {
            if ($position > $target) {
                $nextStop = $stop;
                break;
            }
            $lastStop = $stop;
            $lastPosition = $lastPosition;
        }

        if ($nextStop === null) {
            return $lastStop;
        }

        return RgbColor::fromRgb(
            intval($lastStop->r + (abs($nextStop->r - $lastStop->r) * $target)),
            intval($lastStop->g + (abs($nextStop->g - $lastStop->g) * $target)),
            intval($lastStop->b + (abs($nextStop->b - $lastStop->b) * $target)),
        );
    }
}
