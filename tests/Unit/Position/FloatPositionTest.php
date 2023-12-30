<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Position;

use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Position\FloatPosition;
use PHPUnit\Framework\TestCase;

final class FloatPositionTest extends TestCase
{
    public function testOutOfBounds(): void
    {
        self::assertFalse(FloatPosition::at(0, 0)->outOfBounds(new AxisBounds(0, 10), new AxisBounds(0, 10)));
        self::assertTrue(
            FloatPosition::at(-1, 0)->outOfBounds(new AxisBounds(0, 10), new AxisBounds(0, 10))
        );
    }
}
