<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Shape\SpriteShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Position\FloatPosition;

final class SpriteShapeTest extends ShapeTestCase
{
    /**
     * @dataProvider provideSprite
     * @param array<int,string> $expected
     */
    public function testSprite(SpriteShape $sprite, Marker $marker, array $expected): void
    {
        $canvas = CanvasWidget::default()
            ->marker($marker)
            ->xBounds(AxisBounds::new(0, 34))
            ->yBounds(AxisBounds::new(0, 10))
            ->paint(static function (CanvasContext $context) use ($sprite): void {
                $context->draw($sprite);
            });
        $area = Area::fromDimensions(34, 10);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals(implode("\n", $expected), $buffer->toString());
    }

    /**
     * @return Generator<array{SpriteShape,Marker,array<int,string>}>
     */
    public static function provideSprite(): Generator
    {
        yield 'block line' => [
            new SpriteShape(
                rows: [
                    '█████████████████████████████████',
                ],
                color: AnsiColor::Green,
                alphaChar: ' ',
                xScale: 1,
                yScale: 1,
                position: FloatPosition::at(0, 0)
            ),
            Marker::Block,
            [
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '                                  ',
                    '█████████████████████████████████ ',
            ]
        ];
        yield 'identity' => [
            new SpriteShape(
                rows: [
                    '       █████',
                    '   ████████████████████',
                    ' █████████████████████████',
                    '█████ ███████████████████████',
                    '█████████████████████████████████',
                    '█████████████████████████████   ██',
                    '███  ████████████████████████',
                    '███  ███████████████████████ ',
                    '███  ███████████████████████ ',
                    '███  ████ ████  ████  ██████ ',
                ],
                color: AnsiColor::Green,
                alphaChar: ' ',
                xScale: 1,
                yScale: 1,
                position: FloatPosition::at(0, 0)
            ),
            Marker::Block,
            [
                    '       █████                      ',
                    '   ████████████████████           ',
                    ' █████████████████████████        ',
                    '█████ ███████████████████████     ',
                    '█████████████████████████████████ ',
                    '█████████████████████████████   ██',
                    '███  ████████████████████████     ',
                    '███  ███████████████████████      ',
                    '███  ███████████████████████      ',
                    '███  ████ ████  ████  ██████      ',
            ]
        ];
        yield 'scale to 50%' => [
            new SpriteShape(
                rows: [
                    '       █████',
                    '   ████████████████████',
                    ' █████████████████████████',
                    '█████ ███████████████████████',
                    '█████████████████████████████████',
                    '█████████████████████████████   ██',
                    '███  ████████████████████████',
                    '███  ███████████████████████ ',
                    '███  ███████████████████████ ',
                    '███  ████ ████  ████  ██████ ',
                ],
                color: AnsiColor::Green,
                alphaChar: ' ',
                xScale: 0.5,
                yScale: 0.5,
                position: FloatPosition::at(4, 2)
            ),
            Marker::Braille,
            [

                '                                  ',
                '                                  ',
                '                                  ',
                '      ⣤⣤⣿⣿⣧⣤⣤⣤⣤⣤                  ',
                '    ⢠⣿⣿⢻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣧⣤               ',
                '    ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠛⢻⡄            ',
                '    ⢸⣿ ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡟               ',
                '    ⢸⣿ ⣿⣿⢻⣿⡟⢻⣿⡟⢻⣿⣿⡇               ',
                '                                  ',
                '                                  ',
            ]
        ];
        yield 'scale to 200%' => [
            new SpriteShape(
                rows: [
                    '       █████',
                    '   ████████████████████',
                    ' █████████████████████████',
                    '█████ ███████████████████████',
                    '█████████████████████████████████',
                    '█████████████████████████████   ██',
                    '███  ████████████████████████',
                    '███  ███████████████████████ ',
                    '███  ███████████████████████ ',
                    '███  ████ ████  ████  ██████ ',

                ],
                color: AnsiColor::Green,
                alphaChar: ' ',
                xScale: 2,
                yScale: 2,
                position: FloatPosition::at(4, 2)
            ),
            Marker::Braille,
            [
                '    ⢸⣿⣿⣿⣿⣿⡏⠉⠉⠉⢹⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⡇ ⢸⣿⣿⣿⣿⣿⣿⣿⡇ ',
                '    ⢸⣿⣿⣿⣿⣿⡇   ⢸⣿⣿⣿⣿⣿⣿⣿⡇ ⢸⣿⣿⣿⣿⣿⣿⣿⡇ ',
                '                                  ',
                '                                  ',
            ]
        ];
        yield 'change alpha color' => [
            new SpriteShape(
                rows: [
                    '       █████                      ',
                    '   ████████████████████           ',
                    ' █████████████████████████        ',
                    '█████ ███████████████████████     ',
                    '█████████████████████████████████ ',
                    '█████████████████████████████   ██',
                    '███  ████████████████████████     ',
                    '███  ███████████████████████      ',
                    '███  ███████████████████████      ',
                    '███  ████ ████  ████  ██████      ',
                ],
                color: AnsiColor::Green,
                alphaChar: '█',
                xScale: 1,
                yScale: 1,
                position: FloatPosition::at(0, 0)
            ),
            Marker::Block,
            [
                '███████     ██████████████████████',
                '███                    ███████████',
                '█                         ████████',
                '     █                       █████',
                '                                 █',
                '                             ███  ',
                '   ██                        █████',
                '   ██                       ██████',
                '   ██                       ██████',
                '   ██    █    ██    ██      ██████'
            ]
        ];
    }
}
