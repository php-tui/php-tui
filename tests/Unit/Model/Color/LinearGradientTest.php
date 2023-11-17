<?php

namespace PhpTui\Tui\Tests\Unit\Model\Color;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;

class LinearGradientTest extends TestCase
{
    public function testLinearGradient(): void
    {
        $color = LinearGradient::from(RgbColor::fromRgb(0, 0, 0));
        $color->addStop(0.5, RgbColor::fromRgb(10, 0, 0));
    }
}
