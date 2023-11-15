<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\Line as DTLLine;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Extension\Core\Shape\Circle;
use PhpTui\Tui\Extension\Core\Shape\Line;
use Generator;

class CanvasTest extends WidgetTestCase
{
    public function testFromIntBounds(): void
    {
        $canvas = Canvas::fromIntBounds(1, 320, 2, 240);
        self::assertEquals(AxisBounds::new(1, 320), $canvas->xBounds);
        self::assertEquals(AxisBounds::new(2, 240), $canvas->yBounds);
    }

    public function testDraw(): void
    {
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::filled($area, Cell::fromChar('x'));

        $canvas = Canvas::fromIntBounds(0, 10, 0, 10);
        $canvas->draw(Circle::fromScalars(5, 5, 5)->color(AnsiColor::Green));
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

        $canvas = Canvas::fromIntBounds(0, 5, 0, 5);
        $canvas->draw(
            Circle::fromScalars(1, 1, 1),
            Circle::fromScalars(4, 4, 1),
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
        $horizontalLine = Line::fromScalars(
            0.0,
            0.0,
            10.0,
            0.0,
        )->color(
            AnsiColor::Green
        );
        $verticalLine = Line::fromScalars(
            0.0,
            0.0,
            0.0,
            10.0,
        )->color(
            AnsiColor::Green
        );
        $canvas = Canvas::default()->paint(
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
        yield 'half-block'  => [
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
        $canvas = Canvas::default()->paint(
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
