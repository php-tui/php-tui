<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget;

class BarChartRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideBarChartRender
     * @param array<int,string> $expected
     */
    public function testBarChartRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideBarChartRender(): Generator
    {
        yield 'vertical barchart' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromArray(['B0' => 1, 'B1' => 2])
            ),
            [
                '  █       ',
                '▄ █       ',
                '█ █       ',
                '1 2       ',
                'B B       ',
            ]
           ,
        ];
        yield 'horizontal barchart' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromArray(['B0' => 1, 'B1' => 2])
            )->direction(Direction::Horizontal),
            [
                '  █       ',
                '▄ █       ',
                '█ █       ',
                '1 2       ',
                'B B       ',
            ]
           ,
        ];
    }
}
