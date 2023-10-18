<?php

namespace DTL\PhpTui\Tests\Model\Widget;

use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Widget\FloatPosition;
use PHPUnit\Framework\TestCase;

class FloatPositionTest extends TestCase
{
    public function testOutOfBounds(): void
    {
        self::assertFalse(FloatPosition::at(0, 0)->outOfBounds(new AxisBounds(0, 10), new AxisBounds(0, 10)));
        self::assertTrue(
            FloatPosition::at(-1, 0)->outOfBounds(new AxisBounds(0, 10), new AxisBounds(0, 10))
        );
    }
}
