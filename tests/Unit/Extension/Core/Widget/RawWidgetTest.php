<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;

final class RawWidgetTest extends WidgetTestCase
{
    /**
     * @dataProvider provideRawWidgetRender
     * @param array<int,string> $expected
     */
    public function testRawWidgetRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
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
            BlockWidget::default()->widget(
                RawWidget::new(function (Buffer $buffer): void {
                    $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
                })
            )->padding(Padding::fromScalars(1, 1, 1, 1)),
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
            BlockWidget::default()->widget(
                RawWidget::new(function (Buffer $buffer): void {
                    $buffer->putSpan(Position::at(0, 0), Span::fromString(str_repeat('Hello', 10)), 10);
                })
            )->padding(Padding::fromScalars(1, 1, 1, 1)),
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
