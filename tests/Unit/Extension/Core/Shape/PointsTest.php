<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\Points;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Marker;

class PointsTest extends ShapeTestCase
{
    /**
     * @dataProvider providePoints
     * @param array<int,string> $expected
     */
    public function testPoints(Points $points, array $expected): void
    {
        $canvas = Canvas::default()
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
     * @return Generator<array{Points,array<int,string>}>
     */
    public static function providePoints(): Generator
    {
        yield 'out of bounds' => [
            Points::new([[100,100],[100,100]], AnsiColor::Red),
            [
                '   ',
                '   ',
                '   ',
            ]
        ];
        yield 'points' => [
            Points::new([[0,0],[1,1],[2,2]], AnsiColor::Red),
            [
                '  •',
                ' • ',
                '•  ',
            ]
        ];
    }
}
