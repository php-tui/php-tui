<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\PointsShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;

final class PointsShapeTest extends ShapeTestCase
{
    /**
     * @dataProvider providePoints
     * @param array<int,string> $expected
     */
    public function testPoints(PointsShape $points, array $expected): void
    {
        $canvas = CanvasWidget::default()
            ->marker(Marker::Dot)
            ->xBounds(AxisBounds::new(0, 2))
            ->yBounds(AxisBounds::new(0, 2))
            ->paint(function (CanvasContext $context) use ($points): void {
                $context->draw($points);
            });
        $area = Area::fromDimensions(3, 3);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{PointsShape,array<int,string>}>
     */
    public static function providePoints(): Generator
    {
        yield 'out of bounds' => [
            PointsShape::new([[100,100],[100,100]], AnsiColor::Red),
            [
                '   ',
                '   ',
                '   ',
            ]
        ];
        yield 'points' => [
            PointsShape::new([[0,0],[1,1],[2,2]], AnsiColor::Red),
            [
                '  •',
                ' • ',
                '•  ',
            ]
        ];
    }
}
