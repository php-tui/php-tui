<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\SparklineWidget;
use PhpTui\Tui\Extension\Core\Widget\Sparkline\RenderDirection;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;

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
                "   █▇▆▅▄▃▂▁ ",
            ]
        ];
    }
}

