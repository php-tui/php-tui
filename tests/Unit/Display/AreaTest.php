<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Display;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Widget\Margin;
use PHPUnit\Framework\TestCase;

final class AreaTest extends TestCase
{
    public function testInnerEmpty(): void
    {
        $a = Area::empty();
        self::assertEquals(Area::empty(), $a->inner(Margin::fromScalars(10, 10)));
    }

    public function testInner(): void
    {
        $a = Area::fromDimensions(10, 10);
        self::assertEquals(Area::fromScalars(2, 2, 6, 6), $a->inner(Margin::all(2)));
    }

    public function testWithVerticalMargin(): void
    {
        $a = Area::fromDimensions(10, 10);
        self::assertEquals(Area::fromScalars(0, 2, 10, 6), $a->inner(Margin::vertical(2)));
    }

    public function testWithHorizontalMargin(): void
    {
        $a = Area::fromDimensions(10, 10);
        self::assertEquals(Area::fromScalars(2, 0, 6, 10), $a->inner(Margin::horizontal(2)));
    }
}
