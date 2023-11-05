<?php

namespace PhpTui\Tui\Tests\Widget\Canvas\Shape;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Line;
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
            ->xBounds(AxisBounds::new(0, 10))
            ->yBounds(AxisBounds::new(0, 10))
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
            Line::fromScalars(-1.0, -1.0, 10.0, 10.0),
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
            Line::fromScalars(0.0, 0.0, 10.0, 0.0),
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
            Line::fromScalars(10.0, 10.0, 0.0, 10.0),
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
            Line::fromScalars(0.0, 0.0, 0.0, 10.0),
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
            Line::fromScalars(0.0, 0.0, 10.0, 5.0),
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
            Line::fromScalars(0.0, 0.0, 5.0, 10.0),
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
            Line::fromScalars(10.0, 0.0, 0.0, 5.0),
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
            Line::fromScalars(0.0, 10.0, 5.0, 0.0),
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
