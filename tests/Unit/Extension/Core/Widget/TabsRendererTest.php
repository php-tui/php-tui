<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\TabsWidget;
use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Widget\Widget;

final class TabsRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideTableRender
     * @param array<int,string> $expected
     */
    public function testTableRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideTableRender(): Generator
    {
        yield 'zero tabs' => [
            Area::fromDimensions(20, 2),
            TabsWidget::default(),
            [
                '                    ',
                '                    ',
            ]
           ,
        ];
        yield 'one tab' => [
            Area::fromDimensions(20, 2),
            TabsWidget::default()
                ->titles(
                    Line::fromString('Tab 1'),
                ),
            [
                ' Tab 1              ',
                '                    ',
            ]
           ,
        ];
        yield 'two tabs' => [
            Area::fromDimensions(20, 2),
            TabsWidget::default()
                ->titles(
                    Line::fromString('Tab 1'),
                    Line::fromString('Tab 2'),
                ),
            [
                ' Tab 1 │ Tab 2      ',
                '                    ',
            ]
           ,
        ];
        yield 'select tabs' => [
            Area::fromDimensions(20, 2),
            TabsWidget::default()
                ->select(1)
                ->titles(
                    Line::fromString('Tab 1'),
                    Line::fromString('Tab 2'),
                ),
            [
                ' Tab 1 │ Tab 2      ',
                '                    ',
            ]
           ,
        ];
        yield 'select out of range' => [
            Area::fromDimensions(20, 2),
            TabsWidget::default()
                ->select(100)
                ->titles(
                    Line::fromString('Tab 1'),
                    Line::fromString('Tab 2'),
                ),
            [
                ' Tab 1 │ Tab 2      ',
                '                    ',
            ]
           ,
        ];
    }
}
