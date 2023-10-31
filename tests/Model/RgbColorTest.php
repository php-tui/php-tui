<?php

namespace PhpTui\Tui\Tests\Model;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\RgbColor;

class RgbColorTest extends TestCase
{
    public function testFromRgbOutOfRange(): void
    {
        $this->expectExceptionMessage('red must be in range 0-255 got -1');
        RgbColor::fromRgb(-1, 0, 0);
    }

    public function testFromHsv(): void
    {
        self::assertEquals(RgbColor::fromRgb(128, 153, 26), RgbColor::fromHsv(72, 83, 60));
        self::assertEquals(RgbColor::fromRgb(0, 0, 0), RgbColor::fromHsv(360, 100, 100));
        self::assertEquals(RgbColor::fromRgb(0, 0, 0), RgbColor::fromHsv(0, 0, 0));
    }

    public function testFromOutOfRange(): void
    {
        $this->expectExceptionMessage('hue must be in range 0-360 got 1000');
        RgbColor::fromHsv(1000, 0, 0);
    }

    public function testSaturationOutOfRange(): void
    {
        $this->expectExceptionMessage('saturation must be in range 0-100 got -2');
        RgbColor::fromHsv(0, -2, 0);
    }
}
