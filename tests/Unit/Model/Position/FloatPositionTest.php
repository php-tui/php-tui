<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Position;

use PhpTui\Tui\Model\Graph\AxisBounds;
use PhpTui\Tui\Model\Position\FloatPosition;
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
