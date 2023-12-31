<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\ImageMagick\Shape;

use Generator;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\CanvasRenderer;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImagePainter;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Widget\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

final class ImageShapeTest extends TestCase
{
    /**
     * @dataProvider provideImage
     * @param array<int,string> $expected
     */
    public function testImage(?ImageShape $image, Marker $marker, array $expected): void
    {
        if (null === $image) {
            self::markTestSkipped('Image Magick extension not installed');
        }
        $canvas = CanvasWidget::default()
            ->marker($marker)
            ->xBounds(AxisBounds::new(0, 10))
            ->yBounds(AxisBounds::new(0, 4))
            ->paint(static function (CanvasContext $context) use ($image): void {
                $context->draw($image);
            });
        $area = Area::fromDimensions(10, 4);
        $buffer = Buffer::empty($area);
        (new CanvasRenderer(
            new ImagePainter(),
        ))->render(new NullWidgetRenderer(), $canvas, $buffer, $buffer->area());
        self::assertEquals(implode("\n", $expected), $buffer->toString());
    }

    /**
     * @return Generator<array{?ImageShape,Marker,array<int,string>}>
     */
    public static function provideImage(): Generator
    {
        if (!extension_loaded('imagick')) {
            yield [
                null,
                Marker::Block,
                [''],
            ];

            return;
        }
        yield 'renders image (no colors in this test!)' => [
            ImageShape::fromPath(__DIR__ . '/example.jpg'),
            Marker::Block,
            [
                    '██████████',
                    '██████████',
                    '██████████',
                    '██████████',
            ]
        ];
        yield 'position image' => [
            ImageShape::fromPath(__DIR__ . '/example.jpg')->position(FloatPosition::at(3, 2)),
            Marker::Block,
            [
                    '  ████████',
                    '  ████████',
                    '          ',
                    '          ',
            ]
        ];
    }
}
