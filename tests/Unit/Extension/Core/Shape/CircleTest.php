<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\Circle;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Marker;

class CircleTest extends ShapeTestCase
{
    /**
     * @dataProvider provideCircle
     * @param array<int,string> $expected
     */
    public function testCircle(Circle $circle, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Braille)
            ->xBounds(AxisBounds::new(-10, 10))
            ->yBounds(AxisBounds::new(-10, 10))
            ->paint(function (CanvasContext $context) use ($circle): void {
                $context->draw($circle);
            });
        $area = Area::fromDimensions(10, 5);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Circle,array<int,string>}>
     */
    public static function provideCircle(): Generator
    {
        yield 'circle' => [
            Circle::fromScalars(5, 2, 5),
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
