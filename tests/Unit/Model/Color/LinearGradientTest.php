<?php

namespace PhpTui\Tui\Tests\Unit\Model\Color;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;
use RuntimeException;

class LinearGradientTest extends TestCase
{
    public function testLinearGradient(): void
    {
        $color = LinearGradient::from(RgbColor::fromRgb(0, 0, 0));
        $color->addStop(0.5, RgbColor::fromRgb(10, 0, 0));
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
