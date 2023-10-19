<?php

namespace DTL\PhpTui\Tests\Widget\Canvas\Shape;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Widget\Canvas;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Shape\Points;
use Generator;
use PHPUnit\Framework\TestCase;

class PointsTest extends TestCase
{
    /**
     * @dataProvider providePoints
     * @param array<int,string> $expected
     */
    public function testPoints(Points $points, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Dot)
            ->xBounds(0, 10)
            ->yBounds(0, 10)
            ->paint(function (CanvasContext $context) use ($points): void {
                $context->draw($points);
            });
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
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
        yield 'points' => [
            Points::new([[0,0],[1,1],[2,2]], AnsiColor::Red),
            [
                '•         ',
                ' •        ',
                '  •       ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
        ];
    }
}
