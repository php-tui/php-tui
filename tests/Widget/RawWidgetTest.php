<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\RawWidget;
use Generator;
use PHPUnit\Framework\TestCase;

class RawWidgetTest extends TestCase
{
    /**
     * @dataProvider provideRawWidgetRender
     * @param array<int,string> $expected
     */
    public function testRawWidgetRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $widget->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideRawWidgetRender(): Generator
    {
        yield 'write to buffer' => [
            Area::fromDimensions(10, 10),
            RawWidget::new(function (Buffer $buffer): void {
                $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
            }),
            [
                'Hello     ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'write to buffer in block' => [
            Area::fromDimensions(10, 10),
            Block::default()->widget(
                RawWidget::new(function (Buffer $buffer): void {
                    $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
                })
            )->padding(Padding::fromInts(1, 1, 1, 1)),
            [
                '          ',
                ' Hello    ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'overflow' => [
            Area::fromDimensions(10, 10),
            Block::default()->widget(
                RawWidget::new(function (Buffer $buffer): void {
                    $buffer->putSpan(Position::at(0, 0), Span::fromString(str_repeat('Hello', 10)), 10);
                })
            )->padding(Padding::fromInts(1, 1, 1, 1)),
            [
                '          ',
                ' HelloHel ',
                ' lo       ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
    }
}
