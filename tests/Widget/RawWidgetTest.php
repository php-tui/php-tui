<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\RawWidget;
use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Widget\Paragraph;
use RuntimeException;

class RawWidgetTest extends TestCase
{
    /**
     * @dataProvider provideRawWidgetRender
     * @param array<int,string> $expected
     */
    public function testRawWidgetRender(Area $area, RawWidget $grids, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $grids->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,RawWidget,list<string>}>
     */
    public static function provideRawWidgetRender(): Generator
    {
        yield 'write to buffer' => [
            Area::fromDimensions(10, 10),
            RawWidget::new(function (Buffer $buffer) {
                $buffer->putLine(Position::at(0,0), Line::fromString('Hello'), 5);
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
    }
}
