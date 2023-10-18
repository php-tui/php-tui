<?php

namespace DTL\PhpTui\Tests\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Widget\Canvas;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Shape\Line;
use Generator;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    /**
     * @dataProvider provideLine
     * @param array<int,string> $expected
     */
    public function testLine(Line $line, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Dot)
            ->xBounds(0, 10)
            ->yBounds(0, 10)
            ->paint(function (CanvasContext $context) use ($line): void {
                $context->draw($line);
            });
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Line,array<int,string>}>
     */
    public static function provideLine(): Generator
    {
        yield 'out of bounds' => [
            Line::fromPrimitives(-1.0, -1.0, 10.0, 10.0, AnsiColor::Red),
            [
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
        ];

        yield 'horizontal' => [
            Line::fromPrimitives(0.0, 0.0, 10.0, 0.0, AnsiColor::Red),
            [
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '••••••••••',
            ]
        ];
        yield 'horizontal 2' => [
            Line::fromPrimitives(10.0, 10.0, 0.0, 10.0, AnsiColor::Red),
            [
                '••••••••••',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
        ];

        yield 'vertical' => [
            Line::fromPrimitives(0.0, 0.0, 0.0, 10.0, AnsiColor::Red),
            [
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
                '•         ',
            ]
        ];
        yield 'diagonal' => [
            Line::fromPrimitives(0.0, 0.0, 10.0, 5.0, AnsiColor::Red),
            [
                '          ',
                '          ',
                '          ',
                '          ',
                '         •',
                '       •• ',
                '     ••   ',
                '   ••     ',
                ' ••       ',
                '•         ',
            ]
        ];
        yield 'diagonal dy > dx, y1 < y2' => [
            Line::fromPrimitives(0.0, 0.0, 5.0, 10.0, AnsiColor::Red),
            [
                '    •     ',
                '    •     ',
                '   •      ',
                '   •      ',
                '  •       ',
                '  •       ',
                ' •        ',
                ' •        ',
                '•         ',
                '•         ',
            ]
        ];
        yield 'diagonal dy < dx, x1 < x2' => [
            Line::fromPrimitives(10.0, 0.0, 0.0, 5.0, AnsiColor::Red),
            [
                '          ',
                '          ',
                '          ',
                '          ',
                '•         ',
                ' ••       ',
                '   ••     ',
                '     ••   ',
                '       •• ',
                '         •',
            ]
        ];
        yield 'diagonal dy > dx, y1 > y2' => [
            Line::fromPrimitives(0.0, 10.0, 5.0, 0.0, AnsiColor::Red),
            [
                '•         ',
                '•         ',
                ' •        ',
                ' •        ',
                '  •       ',
                '  •       ',
                '   •      ',
                '   •      ',
                '    •     ',
                '    •     ',
            ]
        ];
    }
}
