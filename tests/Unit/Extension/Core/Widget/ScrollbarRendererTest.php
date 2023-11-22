<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;

class ScrollbarRendererTest extends WidgetTestCase
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
        yield 'vertical' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default(),
            [
                '   ',
                '   ',
                ' X ',
                ' X ',
                ' X ',
                '   ',
                '   ',
            ]
           ,
        ];
        yield 'no begining symbol' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->beginSymbol(null),
            [
                '   ',
                '   ',
                ' X ',
                ' X ',
                ' X ',
                '   ',
                '   ',
            ]
           ,
        ];
        yield 'no end symbol' => [
            Area::fromDimensions(3, 7),
            ScrollbarWidget::default()->endSymbol(null),
            [
                '   ',
                '   ',
                ' X ',
                ' X ',
                ' X ',
                '   ',
                '   ',
            ]
           ,
        ];
        yield 'horizontal top' => [
            Area::fromDimensions(7, 3),
            ScrollbarWidget::default()->orientation(ScrollbarOrientation::HorizontalTop),
            [
                ' xxxx ',
                '      ',
                '      ',
            ]
           ,
        ];
    }
}
