<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\Line;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Marker;

class LineTest extends ShapeTestCase
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
        $this->render($buffer, $canvas);
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
