<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Shape\RectangleShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;

final class RectangleShapeTest extends ShapeTestCase
{
    /**
     * @param array<int,string> $expected
     * @dataProvider provideRectangle
     */
    public function testRectangle(RectangleShape $circle, array $expected): void
    {
        $canvas = CanvasWidget::default()
            ->marker(Marker::Block)
            ->xBounds(AxisBounds::new(0, 10))
            ->yBounds(AxisBounds::new(0, 10))
            ->paint(static function (CanvasContext $context) use ($circle): void {
                $context->draw($circle);
            });
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{RectangleShape,array<int,string>}>
     */
    public static function provideRectangle(): Generator
    {
        yield 'circle' => [
            RectangleShape::fromScalars(0, 0, 10, 10)->color(AnsiColor::Reset),
            [
            '██████████',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '█        █',
            '██████████',
            ]
        ];
    }
}
