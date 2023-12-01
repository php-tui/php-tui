<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarSymbols;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;
use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget\Widget;

final class ScrollbarRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideScrollbarRender
     * @param array<int,string> $expected
     */
    public function testScrollbarRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideScrollbarRender(): Generator
    {
        yield 'no state' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default(),
            [
                '   ',
                '   ',
                '   ',
                '   ',
                '   ',
                '   ',
                '   ',
            ]
           ,
        ];
        yield 'vertical left' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->state(new ScrollbarState(10)),
            [
                '▲  ',
                '█  ',
                '█  ',
                '║  ',
                '║  ',
                '║  ',
                '▼  ',
            ]
           ,
        ];
        yield 'vertical right' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->state(new ScrollbarState(10))->orientation(ScrollbarOrientation::VerticalRight),
            [
                '  ▲',
                '  █',
                '  █',
                '  ║',
                '  ║',
                '  ║',
                '  ▼',
            ]
           ,
        ];
        yield 'no begining symbol' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->state(new ScrollbarState(10))->beginSymbol(null),
            [
                '█  ',
                '█  ',
                '█  ',
                '║  ',
                '║  ',
                '║  ',
                '▼  ',
            ]
           ,
        ];
        yield 'no end symbol' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->state(new ScrollbarState(10))->endSymbol(null),
            [
                '▲  ',
                '█  ',
                '█  ',
                '█  ',
                '║  ',
                '║  ',
                '║  ',
            ]
           ,
        ];
        yield 'double horizontal top' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->state(new ScrollbarState(10))->orientation(ScrollbarOrientation::HorizontalTop),
            [
                '◄██═══►',
                '       ',
                '       ',
            ]
           ,
        ];
        yield 'double horizontal bottom' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->state(new ScrollbarState(10))->orientation(ScrollbarOrientation::HorizontalBottom),
            [
                '       ',
                '       ',
                '◄██═══►',
            ]
           ,
        ];
        yield 'double horizontal bottom mid' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->state(new ScrollbarState(20, 10, 5))->orientation(ScrollbarOrientation::HorizontalBottom),
            [
                '       ',
                '       ',
                '◄══█══►',
            ]
           ,
        ];
        yield 'symbol vertical' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->state(new ScrollbarState(20, 10, 5))->symbols(ScrollbarSymbols::vertical()),
            [
            '↑      ',
            '█      ',
            '↓      ',
            ]
           ,
        ];
        yield 'symbol horizontal' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->state(new ScrollbarState(20, 10, 5))->orientation(ScrollbarOrientation::HorizontalTop)->symbols(ScrollbarSymbols::horizontal()),
            [
            '←──█──→',
            '       ',
            '       ',
            ]
           ,
        ];
    }
}
