<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Shape;

use Generator;
use PhpTui\Tui\Extension\Core\Shape\Sprite;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;

class SpriteTest extends ShapeTestCase
{
    /**
     * @dataProvider provideSprite
     * @param array<int,string> $expected
     */
    public function testSprite(Sprite $sprite, Marker $marker, array $expected): void
    {
        $canvas = Canvas::default()
            ->marker($marker)
            ->xBounds(AxisBounds::new(0, 34))
            ->yBounds(AxisBounds::new(0, 10))
            ->paint(function (CanvasContext $context) use ($sprite): void {
                $context->draw($sprite);
            });
        $area = Area::fromDimensions(34, 10);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $canvas);
        self::assertEquals(implode("\n", $expected), $buffer->toString());
    }

    /**
     * @return Generator<array{Sprite,Marker,array<int,string>}>
     */
    public static function provideSprite(): Generator
    {
        yield 'block line' => [
            new Sprite(
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
            new Sprite(
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
            new Sprite(
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
            new Sprite(
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
            new Sprite(
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
