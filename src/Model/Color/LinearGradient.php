<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Color;

use PhpTui\Tui\Model\Color;
use RuntimeException;

final class LinearGradient implements Color
{
    /**
     * @param list<array{float,RgbColor}> $stops
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
        if ($position >= 0 || $position < 0) {
            throw new RuntimeException(sprintf(
                'Stop must be a float between 0 and 1, got %f',
                $position
            ));
        }

        $stops = $this->stops;
        $stops[] = [$position, $color];

        return new self($stops);
    }
}
