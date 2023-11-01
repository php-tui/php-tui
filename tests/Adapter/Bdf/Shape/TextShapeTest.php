<?php

namespace PhpTui\Tui\Tests\Adapter\Bdf\Shape;

use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use Generator;
use PHPUnit\Framework\TestCase;

class TextShapeTest extends TestCase
{
    /**
     * @dataProvider provideTextShape
     * @param array<int,string> $expected
     */
    public function testTextShape(TextShape $text, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker(Marker::Block)
            ->xBounds(AxisBounds::new(0, 65))
            ->yBounds(AxisBounds::new(0, 6))
            ->paint(function (CanvasContext $context) use ($text): void {
                $context->draw($text);
            });
        $area = Area::fromDimensions(65, 6);
        $buffer = Buffer::empty($area);
        $canvas->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{TextShape,array<int,string>}>
     */
    public static function provideTextShape(): Generator
    {
        yield 'text' => [
            new TextShape(
                font: FontRegistry::default()->get('default'),
                text: 'Hello World',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                '█  █        ██    ██               █   █              ██       █ ',
                '█  █  ███    █     █    ███        █   █  ███  █ ██    █    ██ █ ',
                '████ █   █   █     █   █   █       █ █ █ █   █ ██  █   █   █  ██ ',
                '█  █ █████   █     █   █   █       █ █ █ █   █ █       █   █   █ ',
                '█  █ █       █     █   █   █       ██ ██ █   █ █       █   █  ██ ',
                '█  █  ███   ███   ███   ███        █   █  ███  █      ███   ██ █ ',
            ]
        ];
    }
}
