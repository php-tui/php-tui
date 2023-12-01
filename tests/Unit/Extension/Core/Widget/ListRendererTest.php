<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Corner;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Text\Text;

final class ListRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideRenderList
     * @param array<int,string> $expected
     */
    public function testRenderList(Area $area, ListWidget $itemList, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $itemList);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,ListWidget,array<int,string>}>
     */
    public static function provideRenderList(): Generator
    {
        yield 'simple' => [
            Area::fromDimensions(5, 5),
            ListWidget::default()
                ->items(
                    ListItem::new(Text::fromString('Hello')),
                    ListItem::new(Text::fromString('World')),
                ),
            [
                'Hello',
                'World',
                '     ',
                '     ',
                '     ',
            ]
        ];
        yield 'start from BL corner' => [
            Area::fromDimensions(5, 5),
            ListWidget::default()
                ->startCorner(Corner::BottomLeft)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '     ',
                '4    ',
                '3    ',
                '2    ',
                '1    ',
            ]
        ];
        yield 'highlight' => [
            Area::fromDimensions(5, 5),
            ListWidget::default()
                ->startCorner(Corner::BottomLeft)
                ->select(1)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '     ',
                '  4  ',
                '  3  ',
                '>>2  ',
                '  1  ',
            ]
        ];
        yield 'with offset' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->offset(1)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '2  ',
                '3  ',
            ]
        ];
        yield 'with selected and offset' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->offset(1)
                ->select(2)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '  2',
                '>>3',
            ]
        ];
        yield 'scroll to selected if offset out of range' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->offset(0)
                ->select(3)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '  3',
                '>>4',
            ]
        ];
        yield 'with out of range negative offset' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->offset(-10)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '1  ',
                '2  ',
            ]
        ];
        yield 'with out of range positive offset' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->offset(100)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '4  ',
                '   ',
            ]
        ];
        yield 'with out of range positive selection' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->select(100)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '  3',
                '  4',
            ]
        ];
        yield 'with out of range negative selection' => [
            Area::fromDimensions(3, 2),
            ListWidget::default()
                ->select(-100)
                ->items(
                    ListItem::new(Text::fromString('1')),
                    ListItem::new(Text::fromString('2')),
                    ListItem::new(Text::fromString('3')),
                    ListItem::new(Text::fromString('4')),
                ),
            [
                '>>1',
                '  2',
            ]
        ];
    }
}
