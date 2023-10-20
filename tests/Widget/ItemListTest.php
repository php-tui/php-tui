<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Corner;
use DTL\PhpTui\Model\Widget\Text;
use DTL\PhpTui\Widget\ItemList;
use DTL\PhpTui\Widget\ItemList\ListItem;
use Generator;
use PHPUnit\Framework\TestCase;

class ItemListTest extends TestCase
{
    /**
     * @dataProvider provideRenderItemList
     * @param array<int,string> $expected
     */
    public function testRenderItemList(Area $area, ItemList $itemList, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $itemList->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,ItemList,array<int,string>}>
     */
    public static function provideRenderItemList(): Generator
    {
        yield 'simple' => [
            Area::fromDimensions(5, 5),
            ItemList::default()
                ->items([
                    ListItem::new(Text::raw('Hello')),
                    ListItem::new(Text::raw('World')),
                ]),
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
            ItemList::default()
                ->startCorner(Corner::BottomLeft)
                ->items([
                    ListItem::new(Text::raw('1')),
                    ListItem::new(Text::raw('2')),
                    ListItem::new(Text::raw('3')),
                    ListItem::new(Text::raw('4')),
                ]),
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
            ItemList::default()
                ->startCorner(Corner::BottomLeft)
                ->select(1)
                ->items([
                    ListItem::new(Text::raw('1')),
                    ListItem::new(Text::raw('2')),
                    ListItem::new(Text::raw('3')),
                    ListItem::new(Text::raw('4')),
                ]),
            [
                '     ',
                '  4  ',
                '  3  ',
                '>>2  ',
                '  1  ',
            ]
        ];
    }
}

