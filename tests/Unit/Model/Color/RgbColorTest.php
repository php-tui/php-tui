<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Color;

use InvalidArgumentException;
use PhpTui\Tui\Model\Color\RgbColor;
use PHPUnit\Framework\TestCase;

final class RgbColorTest extends TestCase
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

    public function testFromHexWithValidColor(): void
    {
        $color = RgbColor::fromHex('#1a2b3c');
        self::assertEquals(26, $color->r);
        self::assertEquals(43, $color->g);
        self::assertEquals(60, $color->b);

        $color = RgbColor::fromHex('#d7890b');
        self::assertEquals(215, $color->r);
        self::assertEquals(137, $color->g);
        self::assertEquals(11, $color->b);
    }

    public function testFromHexWithValidShortColor(): void
    {
        $color = RgbColor::fromHex('#cec');
        self::assertEquals(204, $color->r);
        self::assertEquals(238, $color->g);
        self::assertEquals(204, $color->b);
    }

    public function testFromHexWithInvalidColor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RgbColor::fromHex('#foo');
    }

    public function testFromHexWithoutHash(): void
    {
        $color = RgbColor::fromHex('123456');
        self::assertEquals(18, $color->r);
        self::assertEquals(52, $color->g);
        self::assertEquals(86, $color->b);
    }

    public function testFromHexWithInvalidLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RgbColor::fromHex('#12');

        $this->expectException(InvalidArgumentException::class);
        RgbColor::fromHex('#1234567');
    }
}
