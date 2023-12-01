<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\CircleShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;

final class CircleShapeTest extends ShapeTestCase
{
    /**
     * @dataProvider provideCircle
     * @param array<int,string> $expected
     */
    public function testCircle(CircleShape $circle, array $expected): void
    {
        $canvas = CanvasWidget::default()
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
     * @return Generator<array{CircleShape,array<int,string>}>
     */
    public static function provideCircle(): Generator
    {
        yield 'circle' => [
            CircleShape::fromScalars(5, 2, 5),
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
