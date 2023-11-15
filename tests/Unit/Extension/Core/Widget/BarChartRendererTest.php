<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Span;

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
        yield 'barchart' => [
            Area::fromDimensions(10, 1),
            BarChartWidget::default()->ratio(0),
            [
                '  0.00%   ',
            ]
           ,
        ];
    }
}
