<?php

namespace PhpTui\Tui\Tests\Widget\Canvas\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Rectangle;
use Generator;
use PHPUnit\Framework\TestCase;

class RectangleTest extends TestCase
{
    /**
     * @param array<int,string> $expected
     * @dataProvider provideRectangle
     */
    public function testRectangle(Rectangle $circle, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Block)
            ->xBounds(AxisBounds::new(0, 10))
            ->yBounds(AxisBounds::new(0, 10))
            ->paint(function (CanvasContext $context) use ($circle): void {
                $context->draw($circle);
            });
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Rectangle,array<int,string>}>
     */
    public static function provideRectangle(): Generator
    {
        yield 'circle' => [
            Rectangle::fromScalars(0, 0, 10, 10)->color(AnsiColor::Reset),
            [
            '██████████',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '██████████',
            ]
        ];
    }
}
