<?php

namespace DTL\PhpTui\Tests\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Widget\Canvas;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Shape\Rectangle;
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
            ->xBounds(0, 10)
            ->yBounds(0, 10)
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
            Rectangle::fromPrimitives(0, 0, 10, 10, AnsiColor::Reset),
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
