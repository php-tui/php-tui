<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\Line as DTLLine;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Line;
use Generator;
use PHPUnit\Framework\TestCase;

class CanvasTest extends TestCase

{
    public function testFromIntBounds(): void
    {
        $canvas = Canvas::fromIntBounds(1, 320, 2, 240);
        self::assertEquals(AxisBounds::new(1, 320), $canvas->xBounds);
        self::assertEquals(AxisBounds::new(2, 240), $canvas->yBounds);
    }
    /**
     * @dataProvider provideRenderMarker
     * @param string[] $expected
     */
    public function testRenderMarker(Marker $marker, array $expected): void
    {
        $horizontalLine = Line::fromPrimitives(
            0.0,
            0.0,
            10.0,
            0.0,
            AnsiColor::Green
        );
        $verticalLine = Line::fromPrimitives(
            0.0,
            0.0,
            0.0,
            10.0,
            AnsiColor::Green
        );
        $canvas = Canvas::default()->paint(
            function (CanvasContext $context) use ($horizontalLine, $verticalLine): void {
                $context->draw($verticalLine);
                $context->draw($horizontalLine);
            }
        )->xBounds(AxisBounds::new(0.0, 10.0))->yBounds(AxisBounds::new(0.0, 10.0))->marker($marker);
        $area = Area::fromPrimitives(0, 0, 5, 5);
        $buffer = Buffer::filled($area, Cell::fromChar('x'));
        $canvas->render($area, $buffer);
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
        $area = Area::fromPrimitives(0, 0, 5, 5);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
        self::assertEquals([
            '     ',
            '     ',
            '     ',
            '     ',
            'Hello',
        ], $buffer->toLines());
    }
}
