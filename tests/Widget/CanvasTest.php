<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Widget\Canvas;
use DTL\PhpTui\Widget\Canvas\CanvasContext;
use DTL\PhpTui\Widget\Canvas\Line;
use Generator;
use PHPUnit\Framework\TestCase;

class CanvasTest extends TestCase
{
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
            AnsiColor::Reset
        );
        $verticalLine = Line::fromPrimitives(
            0.0,
            0.0,
            0.0,
            10.0,
            AnsiColor::Reset
        );
        $canvas = Canvas::default()->paint(
            function (CanvasContext $context) use ($horizontalLine, $verticalLine) {
                $context->draw($verticalLine);
                $context->draw($horizontalLine);
            }
        )->xBounds(0.0, 10.0)->yBounds(0.0, 10.0);
        $area = Area::fromPrimitives(0, 0, 5, 5);
        $buffer = Buffer::empty($area);
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
                '▄▄▄▄▄"',
            ]
        ];
    }
}
