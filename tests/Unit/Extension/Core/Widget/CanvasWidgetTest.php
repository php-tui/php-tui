<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\CircleShape;
use PhpTui\Tui\Extension\Core\Shape\LineShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Display\Cell;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Text\Line as DTLLine;

final class CanvasWidgetTest extends WidgetTestCase
{
    public function testFromIntBounds(): void
    {
        $canvas = CanvasWidget::fromIntBounds(1, 320, 2, 240);
        self::assertEquals(AxisBounds::new(1, 320), $canvas->xBounds);
        self::assertEquals(AxisBounds::new(2, 240), $canvas->yBounds);
    }

    public function testDraw(): void
    {
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::filled($area, Cell::fromChar('x'));

        $canvas = CanvasWidget::fromIntBounds(0, 10, 0, 10);
        $canvas->draw(CircleShape::fromScalars(5, 5, 5)->color(AnsiColor::Green));
        $this->render($buffer, $canvas);
        $expected = [
            'x⢀⡴⠋⠉⠉⠳⣄xx',
            '⢀⡞xxxxx⠘⣆x',
            '⡼xxxxxxx⠸⡄',
            '⡇xxxxxxxx⡇',
            '⡇xxxxxxxx⣇',
            '⡇xxxxxxxx⡇',
            '⡇xxxxxxxx⡇',
            '⢹⡀xxxxxx⣸⠁',
            'x⢳⡀xxxx⣰⠃x',
            'xx⠙⠦⢤⠤⠞⠁xx',
        ];
        self::assertEquals($expected, $buffer->toLines());

        $buffer = Buffer::filled($area, Cell::fromChar('x'));
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }

    public function testDrawMultiple(): void
    {
        $area = Area::fromDimensions(5, 5);
        $buffer = Buffer::filled($area, Cell::fromChar('x'));

        $canvas = CanvasWidget::fromIntBounds(0, 5, 0, 5);
        $canvas->draw(
            CircleShape::fromScalars(1, 1, 1),
            CircleShape::fromScalars(4, 4, 1),
        );
        $this->render($buffer, $canvas);
        $expected = [
            'xx⢸⠉⣇',
            'xx⠸⣄⡇',
            '⣀⡀xxx',
            '⡇⢹xxx',
            '⢧⠼xxx',
        ];
        self::assertEquals($expected, $buffer->toLines());
    }

    /**
     * @dataProvider provideRenderMarker
     * @param string[] $expected
     */
    public function testRenderMarker(Marker $marker, array $expected): void
    {
        $horizontalLine = LineShape::fromScalars(
            0.0,
            0.0,
            10.0,
            0.0,
        )->color(
            AnsiColor::Green
        );
        $verticalLine = LineShape::fromScalars(
            0.0,
            0.0,
            0.0,
            10.0,
        )->color(
            AnsiColor::Green
        );
        $canvas = CanvasWidget::default()->paint(
            function (CanvasContext $context) use ($horizontalLine, $verticalLine): void {
                $context->draw($verticalLine);
                $context->draw($horizontalLine);
            }
        )->xBounds(AxisBounds::new(0.0, 10.0))->yBounds(AxisBounds::new(0.0, 10.0))->marker($marker);
        $area = Area::fromScalars(0, 0, 5, 5);
        $buffer = Buffer::filled($area, Cell::fromChar('x'));
        $this->render($buffer, $canvas);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Marker,string[]}>
     */
    public static function provideRenderMarker(): Generator
    {
        yield [
            Marker::Bar,
            [
                '▄xxxx',
                '▄xxxx',
                '▄xxxx',
                '▄xxxx',
                '▄▄▄▄▄',
            ]
        ];
        yield [
            Marker::Block,
            [
                '█xxxx',
                '█xxxx',
                '█xxxx',
                '█xxxx',
                '█████',
            ]
        ];
        yield [
            Marker::Dot,
            [
                '•xxxx',
                '•xxxx',
                '•xxxx',
                '•xxxx',
                '•••••',
            ]
        ];
        yield 'braille' => [
            Marker::Braille,
            [
                '⡇xxxx',
                '⡇xxxx',
                '⡇xxxx',
                '⡇xxxx',
                '⣇⣀⣀⣀⣀',
            ]
        ];
        yield 'half-block' => [
            Marker::HalfBlock,
            [
                '█xxxx',
                '█xxxx',
                '█xxxx',
                '█xxxx',
                '█▄▄▄▄',
            ],
        ];
    }

    public function testLabels(): void
    {
        $canvas = CanvasWidget::default()->paint(
            function (CanvasContext $context): void {
                $context->print(0, 0, DTLLine::fromString('Hello'));
            }
        )->xBounds(AxisBounds::new(0.0, 10.0))->yBounds(AxisBounds::new(0.0, 5));
        $area = Area::fromScalars(0, 0, 5, 5);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals([
            '     ',
            '     ',
            '     ',
            '     ',
            'Hello',
        ], $buffer->toLines());
    }
}
