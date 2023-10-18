<?php

namespace DTL\PhpTui\Tests\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Widget\Canvas;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Shape\Circle;
use Generator;
use PHPUnit\Framework\TestCase;

class CircleTest extends TestCase
{
    /**
     * @dataProvider provideCircle
     * @param array<int,string> $expected
     */
    public function testCircle(Circle $circle, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Braille)
            ->xBounds(-10, 10)
            ->yBounds(-10, 10)
            ->paint(function (CanvasContext $context) use ($circle): void {
                $context->draw($circle);
            });
        $area = Area::fromDimensions(10, 5);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Circle,array<int,string>}>
     */
    public static function provideCircle(): Generator
    {
        yield 'circle' => [
            Circle::fromPrimitives(5, 2, 5, AnsiColor::Reset),
            [
            '     ⢀⣠⢤⣀ ',
            '    ⢰⠋  ⠈⣇',
            '    ⠘⣆⡀ ⣠⠇',
            '      ⠉⠉⠁ ',
            '          ',
            ]
        ];
    }
}
