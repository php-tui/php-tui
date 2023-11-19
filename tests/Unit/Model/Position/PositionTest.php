<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Position;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Position\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function testReturnsIndexForPosition(): void
    {
        self::assertEquals(
            55,
            (new Position(5, 5))->toIndex(Area::fromScalars(0, 0, 10, 10))
        );
    }

    public function testThrowsExceptionIfOutOfRange(): void
    {
        $this->expectExceptionMessage('Position (15,5) outside of area @(0,0) of 10x10 when trying to get index');
        self::assertEquals(
            55,
            (new Position(15, 5))->toIndex(Area::fromScalars(0, 0, 10, 10))
        );
    }

    public function testCreatesPositionFromIndex(): void
    {
        $position = Position::fromIndex(55, Area::fromScalars(0, 0, 10, 10));
        self::assertEquals(5, $position->x);
        self::assertEquals(5, $position->y);
    }

    public function testThrowsExceptionIfIndexOutOfRange(): void
    {
        $this->expectExceptionMessage('outside of area');
        Position::fromIndex(100, Area::fromScalars(0, 0, 10, 10));
    }

    public function testThrowsExceptionIfNegativeX(): void
    {
        $this->expectExceptionMessage('Neither X nor Y values can be less than zero, got [-1, 2]');
        Position::at(-1, 2);
    }

    public function testThrowsExceptionIfNegativeY(): void
    {
        $this->expectExceptionMessage('Neither X nor Y values can be less than zero, got [1, -2]');
        Position::at(1, -2);
    }
}
