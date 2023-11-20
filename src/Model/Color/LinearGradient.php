<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Color;

use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position\FractionalPosition;
use RuntimeException;

/**
 * Multi-stop linear gradient with optional angle and point of origin.
 *
 * This color is not supported by all widgets. In the case that the widget does
 * not support gradient fills the first stop color will be used.
 */
final class LinearGradient implements Color
{
    /**
     * @param non-empty-list<array{float,RgbColor}> $stops
     */
    private function __construct(
        private array $stops,
        private readonly float $angle,
        private readonly FractionalPosition $origin
    ) {
    }

    /**
     * Rotate the gradient by the given number of degrees.
     */
    public function withDegrees(float $degrees): self
    {
        return new self($this->stops, deg2rad($degrees), $this->origin);
    }

    /**
     * Specify the origin of the rotation as a fractional position.
     */
    public function withOrigin(FractionalPosition $origin): self
    {
        return new self(
            $this->stops,
            $this->angle,
            $origin
        );
    }

    /**
     * Create a new gradient starting from this color at the first stop.
     */
    public static function from(RgbColor $color): self
    {
        return new self([[0, $color]], 0, FractionalPosition::at(0, 0));
    }

    public function debugName(): string
    {
        return sprintf(
            'LinearGradient(deg: %d, origin: %s, stops: [%s])',
            rad2deg($this->angle),
            $this->origin->__toString(),
            implode(', ', array_map(function (array $stop): string {
                return sprintf('%s@%.2f', $stop[1]->debugName(), $stop[0]);
            }, $this->stops))
        );
    }

    /**
     * Add a stop at the given fractional offset (0-1)
     */
    public function addStop(float $position, RgbColor $color): self
    {
        if ($position > 1 || $position < 0) {
            throw new RuntimeException(sprintf(
                'Stop must be a float between 0 and 1, got %f',
                $position
            ));
        }

        $stops = $this->stops;
        $stops[] = [$position, $color];

        return new self($stops, $this->angle, $this->origin);
    }

    public function at(FractionalPosition $position): RgbColor
    {
        $position = $position->translate($this->origin->invert());
        $position = $position->rotate($this->angle);
        $position = $position->translate($this->origin);
        $fraction = max(0, $position->x);

        return $this->atFraction($fraction);
    }

    private function atFraction(float $fraction): RgbColor
    {
        // determine last stop
        $stops = $this->stops;
        usort($stops, static fn (array $s1, array $s2): int => $s1[0] <=> $s2[0]);
        [$lastPosition, $lastStop] = array_shift($stops);

        $nextStop = null;
        $nextPosition = null;
        foreach ($stops as [$stopPos, $stop]) {
            if ($stopPos > $fraction) {
                $nextStop = $stop;
                $nextPosition = $stopPos;

                break;
            }
            $lastStop = $stop;
            $lastPosition = $stopPos;
        }

        if ($nextStop === null || $nextPosition === null) {
            return $lastStop;
        }

        $d = $fraction - $lastPosition;
        $l = $nextPosition - $lastPosition;
        $r = $d / $l;

        return RgbColor::fromRgb(
            $this->interpolate($lastStop->r, $nextStop->r, $r),
            $this->interpolate($lastStop->g, $nextStop->g, $r),
            $this->interpolate($lastStop->b, $nextStop->b, $r),
        );
    }

    private function interpolate(float $c1, float $c2, float $r): int
    {
        $d = $c2 - $c1;

        return (int) ($d * $r + $c1);
    }
}
