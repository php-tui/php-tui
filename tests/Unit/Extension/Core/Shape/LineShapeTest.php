<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\LineShape;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Display\Buffer;

final class LineShapeTest extends ShapeTestCase
{
    /**
     * @dataProvider provideLine
     * @param array<int,string> $expected
     */
    public function testLine(LineShape $line, array $expected): void
    {
        $canvas = CanvasWidget::default()
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
     * @return Generator<array{LineShape,array<int,string>}>
     */
    public static function provideLine(): Generator
    {
        yield 'out of bounds' => [
            LineShape::fromScalars(-1.0, -1.0, 10.0, 10.0),
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
            LineShape::fromScalars(0.0, 0.0, 10.0, 0.0),
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
            LineShape::fromScalars(10.0, 10.0, 0.0, 10.0),
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
            LineShape::fromScalars(0.0, 0.0, 0.0, 10.0),
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
            LineShape::fromScalars(0.0, 0.0, 10.0, 5.0),
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
            LineShape::fromScalars(0.0, 0.0, 5.0, 10.0),
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
            LineShape::fromScalars(10.0, 0.0, 0.0, 5.0),
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
            LineShape::fromScalars(0.0, 10.0, 5.0, 0.0),
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
