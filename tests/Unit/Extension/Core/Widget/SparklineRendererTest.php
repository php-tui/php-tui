<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Sparkline\RenderDirection;
use PhpTui\Tui\Extension\Core\Widget\SparklineWidget;
use PhpTui\Tui\Widget\Widget;

final class SparklineRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideSparklineRender
     * @param array<int,string> $expected
     */
    public function testSparklineRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideSparklineRender(): Generator
    {
        yield 'default' => [
            Area::fromDimensions(20, 2),
            SparklineWidget::default(),
            [
                '                    ',
                '                    ',
            ]
           ,
        ];
        yield 'sparkline' => [
            Area::fromDimensions(12, 1),
            SparklineWidget::fromData(...range(0, 8)),
            [
                ' ▁▂▃▄▅▆▇█   ',
            ]
           ,
        ];
        yield 'right to left' => [
            Area::fromDimensions(12, 1),
            SparklineWidget::fromData(...range(0, 8))->direction(RenderDirection::RightToLeft),
            [
                '   █▇▆▅▄▃▂▁ ',
            ]
        ];

        yield 'taller' => [
            Area::fromDimensions(12, 2),
            SparklineWidget::fromData(...range(0, 8))->direction(RenderDirection::RightToLeft),
            [
            '   █▆▄▂     ',
            '   █████▆▄▂ ',
            ]
        ];
        yield 'with max' => [
            Area::fromDimensions(12, 1),
            SparklineWidget::fromData(...range(0, 8))->direction(RenderDirection::RightToLeft)->max(20),
            [
                '   ▃▃▂▂▂▁▁  '
            ]
        ];
    }
}
