<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Color;

use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LinearGradientTest extends TestCase
{
    /**
     * Get the gradient at 0.25 where R at 0 is 0 and at 0.5 it is 10
     *
     *   0  0.25  0.5    0.75   1
     *   +----------------------+
     *   | 2 4 6 8 | 10.0
     *        |
     *     position
     */
    public function testLinearGradientUp(): void
    {
        $color = LinearGradient::from(RgbColor::fromRgb(0, 0, 0));
        $color = $color->addStop(0.5, RgbColor::fromRgb(10, 0, 0));
        self::assertEquals('RGB(10, 0, 0)', $color->at(0.5)->debugName());
        self::assertEquals('RGB(5, 0, 0)', $color->at(0.25)->debugName());
        self::assertEquals('RGB(2, 0, 0)', $color->at(0.1)->debugName());
        self::assertEquals('RGB(0, 0, 0)', $color->at(0.0)->debugName());
        self::assertEquals('RGB(10, 0, 0)', $color->at(0.75)->debugName());
    }

    public function testLinearGradientDown(): void
    {
        $color = LinearGradient::from(RgbColor::fromRgb(50, 10, 100));
        $color = $color->addStop(0.5, RgbColor::fromRgb(0, 90, 110));
        self::assertEquals('RGB(25, 50, 105)', $color->at(0.25)->debugName());
        self::assertEquals('RGB(0, 90, 110)', $color->at(0.5)->debugName());
    }
    public function testLinearGradientThreeStops(): void
    {
        $color = LinearGradient::from(
            RgbColor::fromRgb(100, 0, 0)
        )->addStop(
            0.5,
            RgbColor::fromRgb(50, 255, 50)
        )->addStop(
            1,
            RgbColor::fromRgb(0, 255, 255)
        );
        self::assertEquals('RGB(25, 63, 12)', $color->at(0.75)->debugName());
        self::assertEquals('RGB(75, 127, 25)', $color->at(0.25)->debugName());
        self::assertEquals('RGB(87, 63, 12)', $color->at(0.125)->debugName());
    }

    public function testAddStopAbove1(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stop must be a float between 0 and 1, got 2.000000');
        LinearGradient::from(RgbColor::fromRgb(0, 0, 0))->addStop(2, RgbColor::fromRgb(10, 0, 0));
    }
    public function testAddStopLessThan0(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stop must be a float between 0 and 1, got -1.000000');
        LinearGradient::from(RgbColor::fromRgb(0, 0, 0))->addStop(-1, RgbColor::fromRgb(10, 0, 0));
    }
}
