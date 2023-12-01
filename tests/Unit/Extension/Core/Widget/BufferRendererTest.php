<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Widget\Widget;

final class BufferRendererTest extends WidgetTestCase
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
            BufferWidget::new(function (BufferContext $context): void {
                $context->buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
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
                BufferWidget::new(function (BufferContext $context): void {
                    $context->buffer->putLine(Position::at($context->area->left(), $context->area->top()), Line::fromString('Hello'), 5);
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
                BufferWidget::new(function (BufferContext $context): void {
                    $context->buffer->putSpan(
                        Position::at($context->area->left(), $context->area->top()),
                        Span::fromString(str_repeat('Hello', 10)),
                        10
                    );
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
