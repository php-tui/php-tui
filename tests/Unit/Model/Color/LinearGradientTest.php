<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Color;

use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;
use PhpTui\Tui\Model\Widget\FractionalPosition;
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
        self::assertEquals('RGB(0, 0, 0)', $color->at(FractionalPosition::at(0.0, 0))->debugName());
        self::assertEquals('RGB(10, 0, 0)', $color->at(FractionalPosition::at(0.5, 0))->debugName());
        self::assertEquals('RGB(5, 0, 0)', $color->at(FractionalPosition::at(0.25, 0))->debugName());
        self::assertEquals('RGB(2, 0, 0)', $color->at(FractionalPosition::at(0.1, 0))->debugName());
        self::assertEquals('RGB(0, 0, 0)', $color->at(FractionalPosition::at(0.0, 0))->debugName());
        self::assertEquals('RGB(10, 0, 0)', $color->at(FractionalPosition::at(0.75, 0))->debugName());
        self::assertEquals('RGB(10, 0, 0)', $color->at(FractionalPosition::at(1, 0))->debugName());
    }

    public function testLinearGradientDown(): void
    {
        $color = LinearGradient::from(RgbColor::fromRgb(50, 10, 100));
        $color = $color->addStop(0.5, RgbColor::fromRgb(0, 90, 110));
        self::assertEquals('RGB(25, 50, 105)', $color->at(FractionalPosition::at(0.25, 0))->debugName());
        self::assertEquals('RGB(0, 90, 110)', $color->at(FractionalPosition::at(0.5, 0))->debugName());
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
        self::assertEquals('RGB(75, 127, 25)', $color->at(FractionalPosition::at(0.25, 0))->debugName());
        self::assertEquals('RGB(87, 63, 12)', $color->at(FractionalPosition::at(0.125, 0))->debugName());
        self::assertEquals('RGB(25, 255, 152)', $color->at(FractionalPosition::at(0.75, 0))->debugName());
    }

    public function testRotateZero(): void
    {
        $color = LinearGradient::from(
            RgbColor::fromRgb(0, 0, 0)
        )->addStop(
            1,
            RgbColor::fromRgb(255, 255, 255)
        )->withDegrees(0)->withOrigin(FractionalPosition::at(0.5, 0.5));

        self::assertEquals('RGB(0, 0, 0)', $color->at(FractionalPosition::at(0, 0))->debugName());
        self::assertEquals('RGB(127, 127, 127)', $color->at(FractionalPosition::at(0.5, 0))->debugName());
        self::assertEquals('RGB(255, 255, 255)', $color->at(FractionalPosition::at(1, 0))->debugName());
        self::assertEquals('RGB(255, 255, 255)', $color->at(FractionalPosition::at(1, 1))->debugName());
    }

    public function testFractionCannotBeLessThanZero(): void
    {
        $color = LinearGradient::from(
            RgbColor::fromRgb(0, 0, 0)
        )->addStop(
            1,
            RgbColor::fromRgb(255, 127, 0)
        )->withDegrees(300)->withOrigin(FractionalPosition::at(0.5, 0.5));

        self::assertEquals('RGB(0, 0, 0)', $color->at(FractionalPosition::at(0, 0))->debugName());
        self::assertEquals('RGB(17, 8, 0)', $color->at(FractionalPosition::at(0.5, 0))->debugName());
    }

    public function testBounds(): void
    {
        for ($at = 0; $at <= 1; $at += 0.1) {
            for ($origin = 0; $origin <= 1; $origin += 0.1) {
                for ($d = 0; $d < 360; $d += 45) {
                    $color = LinearGradient::from(
                        RgbColor::fromRgb(0, 0, 0)
                    )->addStop(
                        1,
                        RgbColor::fromRgb(255, 255, 255)
                    )->withDegrees($d)->withOrigin(FractionalPosition::at($origin, $origin));
                    $color->at(FractionalPosition::at($at, $at));
                }
            }
        }
        $this->addToAssertionCount(1);
    }

    public function testToString(): void
    {
        $color = LinearGradient::from(
            RgbColor::fromRgb(0, 0, 0)
        )->addStop(
            1,
            RgbColor::fromRgb(255, 255, 255)
        )->withDegrees(90)->withOrigin(FractionalPosition::at(0.5, 0.5));

        self::assertEquals('LinearGradient(deg: 90, origin: [0.50, 0.50], stops: [RGB(0, 0, 0)@0.00, RGB(255, 255, 255)@1.00])', $color->debugName());

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
