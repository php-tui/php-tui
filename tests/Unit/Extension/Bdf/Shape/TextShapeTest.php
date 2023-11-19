<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Bdf\Shape;

use Generator;
use PhpTui\Tui\Extension\Bdf\FontRegistry;
use PhpTui\Tui\Extension\Bdf\Shape\TextRenderer;
use PhpTui\Tui\Extension\Bdf\Shape\TextShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Position\FloatPosition;
use PhpTui\Tui\Model\Widget\CanvasRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

class TextShapeTest extends TestCase
{
    /**
     * @dataProvider provideTextShape
     * @param array<int,string> $expected
     */
    public function testTextShape(TextShape $text, array $expected): void
    {
        $canvas = CanvasWidget::fromIntBounds(0, 65, 0, 6)
            ->marker(Marker::Block)
            ->paint(function (CanvasContext $context) use ($text): void {
                $context->draw($text);
            });
        $area = Area::fromDimensions(65, 6);
        $buffer = Buffer::empty($area);
        (new CanvasRenderer(new TextRenderer(FontRegistry::default())))->render(new NullWidgetRenderer(), $canvas, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{TextShape,array<int,string>}>
     */
    public static function provideTextShape(): Generator
    {
        yield 'text' => [
            new TextShape(
                font: 'default',
                text: 'Hello World',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                '█   █        ██    ██               █   █              ██       █',
                '█   █  ███    █     █    ███        █   █  ███  █ ██    █    ██ █',
                '█████ █   █   █     █   █   █       █ █ █ █   █ ██  █   █   █  ██',
                '█   █ █████   █     █   █   █       █ █ █ █   █ █       █   █   █',
                '█   █ █       █     █   █   █       ██ ██ █   █ █       █   █  ██',
                '█   █  ███   ███   ███   ███        █   █  ███  █      ███   ██ █',
            ]
        ];
        yield 'scale x' => [
            new TextShape(
                font: 'default',
                scaleX: 2,
                text: 'Hello',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                '██      ██                ████        ████                       ',
                '██      ██    ██████        ██          ██        ██████         ',
                '██████████  ██      ██      ██          ██      ██      ██       ',
                '██      ██  ██████████      ██          ██      ██      ██       ',
                '██      ██  ██              ██          ██      ██      ██       ',
                '██      ██    ██████      ██████      ██████      ██████         ',
            ]
        ];
        yield 'scale y' => [
            new TextShape(
                font: 'default',
                scaleY: 2,
                text: 'Hello World',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                '█████ █████   █     █   █   █       █ █ █ █   █ ██  █   █   █  ██',
                '█   █ █████   █     █   █   █       █ █ █ █   █ █       █   █   █',
                '█   █ █       █     █   █   █       ██ ██ █   █ █       █   █  ██',
                '█   █ █       █     █   █   █       ██ ██ █   █ █       █   █  ██',
                '█   █  ███   ███   ███   ███        █   █  ███  █      ███   ██ █',
                '█   █  ███   ███   ███   ███        █   █  ███  █      ███   ██ █',
            ]
        ];
    }

    /**
     * @dataProvider provideScale
     * @param array<int,string> $expected
     */
    public function testScale(Area $area, int $boundsX, int $boundsY, TextShape $text, array $expected): void
    {
        $canvas = CanvasWidget::fromIntBounds(0, $boundsX, 0, $boundsY)
            ->marker(Marker::Block)
            ->paint(function (CanvasContext $context) use ($text): void {
                $context->draw($text);
            });
        $buffer = Buffer::empty($area);
        (new CanvasRenderer(
            new TextRenderer(FontRegistry::default())
        ))->render(
            new NullWidgetRenderer(),
            $canvas,
            $buffer
        );
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area, int, int, TextShape,array<int,string>}>
     */
    public static function provideScale(): Generator
    {
        yield 'canvas more narrow than area' => [
            Area::fromDimensions(12, 6),
            6,
            6,
            new TextShape(
                font: 'default',
                text: 'O',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                ' ██████████ ',
                ' ██      ██ ',
                ' ██      ██ ',
                ' ██      ██ ',
                ' ██      ██ ',
                '   ██████   ',
            ]
        ];
        yield 'canvas more short than area' => [
            Area::fromDimensions(6, 12),
            6,
            6,
            new TextShape(
                font: 'default',
                text: 'O',
                color: AnsiColor::Green,
                position: FloatPosition::at(0, 0),
            ),
            [
                '█████ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                '█   █ ',
                ' ███  ',
                ' ███  ',
            ]
        ];
    }
}
