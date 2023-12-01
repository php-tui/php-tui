<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\RectangleShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;

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
            ->paint(function (CanvasContext $context) use ($circle): void {
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
