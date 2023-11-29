<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\SparklineWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;

final class SparklineRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideTableRender
     * @param array<int,string> $expected
     */
    public function testTableRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideTableRender(): Generator
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
            Area::fromDimensions(20, 2),
            SparklineWidget::fromData(10, 1, 2, 3),
            [
                '▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅▅',
                '                    ',
            ]
           ,
        ];
    }
}

