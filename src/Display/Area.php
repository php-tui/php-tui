<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Margin;
use RuntimeException;
use Stringable;

final class Area implements Stringable
{
    public function __construct(
        public Position $position,
        /**
         * @var int<0,max>
         */
        public int $width,
        /**
         * @var int<0,max>
         */
        public int $height,
    ) {
        /** @phpstan-ignore-next-line */
        if ($width < 0 || $height < 0) {
            throw new RuntimeException(sprintf(
                'Neither width nor height can be less than 0, got %dx%d',
                $width,
                $height
            ));
        }
    }

    public function __toString(): string
    {
        return sprintf('@%s of %sx%s', $this->position->__toString(), $this->width, $this->height);
    }

    /**
     * @param int<0,max> $x
     * @param int<0,max> $y
     * @param int<0,max> $width
     * @param int<0,max> $height
     */
    public static function fromScalars(int $x, int $y, int $width, int $height): self
    {
        return new self(new Position($x, $y), $width, $height);
    }

    public function area(): int
    {
        return $this->width * $this->height;
    }

    /**
     * @return int<0,max>
     */
    public function left(): int
    {
        return $this->position->x;
    }

    /**
     * @return int<0,max>
     */
    public function right(): int
    {
        return $this->position->x + $this->width;
    }

    /**
     * @return int<0,max>
     */
    public function top(): int
    {
        return $this->position->y;
    }

    /**
     * @return int<0,max>
     */
    public function bottom(): int
    {
        return $this->position->y + $this->height;
    }

    /**
     * @param int<0,max> $width
     * @param int<0,max> $height
     */
    public static function fromDimensions(int $width, int $height): self
    {
        return self::fromScalars(0, 0, $width, $height);
    }

    public static function empty(): self
    {
        return new self(Position::at(0, 0), 0, 0);
    }

    public function inner(Margin $margin): self
    {
        if ($this->width < 2 * $margin->horizontal || $this->height < 2 * $margin->vertical) {
            return self::empty();
        }

        return self::fromScalars(
            $this->position->x + $margin->horizontal,
            $this->position->y + $margin->vertical,
            max(0, $this->width - 2 * $margin->horizontal),
            max(0, $this->height - 2 * $margin->vertical),
        );
    }

    /**
     * @return array{int,int,int,int}
     */
    public function toArray(): array
    {
        return [
            $this->position->x,
            $this->position->y,
            $this->width,
            $this->height,
        ];
    }

    public function isEmpty(): bool
    {
        return $this->width === 0 || $this->height === 0;
    }

    /**
     * @param int<0,max> $y
     */
    public function withY(int $y): self
    {
        return new self(
            $this->position->withY($y),
            $this->width,
            $this->height,
        );
    }

    /**
     * @param int<0,max> $x
     */
    public function withX(int $x): self
    {
        return new self(
            $this->position->withX($x),
            $this->width,
            $this->height,
        );
    }

    public function containsPosition(Position $position): bool
    {
        return !($position->x < $this->left() ||
        $position->x >= $this->right() ||
        $position->y >= $this->bottom() ||
        $position->y < $this->top());
    }
}
