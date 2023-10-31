<?php

namespace PhpTui\Tui\Tests\Widget\Canvas\Shape;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Tests\Model\Widget\FloatPositionTest;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Widget\Canvas\Shape\Sprite;

class SpriteTest extends TestCase
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
        $canvas->render($area, $buffer);
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
                position: FloatPosition::at(0,0)
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
                position: FloatPosition::at(0,0)
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
                position: FloatPosition::at(4,2)
            ),
            Marker::Braille,
            [

                '                                  ',
                '                                  ',
                '                                  ',
                '      ⣀⣀⣒⣒⣂⣀⣀⣀⣀⣀                  ',
                '    ⢀⣒⣒⢒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣂⣀               ',
                '    ⢐⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⠒⢒⡀            ',
                '    ⢐⣒ ⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⣒⡒               ',
                '    ⢐⣒ ⣒⣒⢒⣒⡒⢒⣒⡒⢒⣒⣒⡂               ',
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
                position: FloatPosition::at(0,0)
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
