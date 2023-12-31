<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Bdf\Shape;

use Generator;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Bdf\FontRegistry;
use PhpTui\Tui\Extension\Bdf\Shape\TextRenderer;
use PhpTui\Tui\Extension\Bdf\Shape\TextShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasRenderer;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Widget\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

final class TextShapeTest extends TestCase
{
    /**
     * @dataProvider provideTextShape
     * @param array<int,string> $expected
     */
    public function testTextShape(TextShape $text, array $expected): void
    {
        $canvas = CanvasWidget::fromIntBounds(0, 65, 0, 6)
            ->marker(Marker::Block)
            ->paint(static function (CanvasContext $context) use ($text): void {
                $context->draw($text);
            });
        $area = Area::fromDimensions(65, 6);
        $buffer = Buffer::empty($area);
        (new CanvasRenderer(new TextRenderer(FontRegistry::default())))->render(new NullWidgetRenderer(), $canvas, $buffer, $buffer->area());
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
            ->paint(static function (CanvasContext $context) use ($text): void {
                $context->draw($text);
            });
        $buffer = Buffer::empty($area);
        (new CanvasRenderer(
            new TextRenderer(FontRegistry::default())
        ))->render(
            new NullWidgetRenderer(),
            $canvas,
            $buffer,
            $buffer->area(),
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
