<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\BarChart\Bar;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
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
        yield 'zero dimension' => [
            Area::fromDimensions(0, 0),
            BarChartWidget::default()->data(
                BarGroup::fromArray(['B0' => 1, 'B1' => 2])
            ),
            [
                '',
            ]
           ,
        ];
        yield 'zero values' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromArray(['B0' => 0, 'B1' => 0])
            ),
            [
                '          ',
                '          ',
                '          ',
                '0 0       ',
                'B B       ',
            ]
           ,
        ];
        yield 'negative values' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromArray(['B0' => -1, 'B1' => 2])
            ),
            [
                '  █       ',
                '  █       ',
                '  █       ',
                '█ 2       ',
                'B B       ',
            ]
           ,
        ];
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
                'B0 1██    ',
                '          ',
                'B1 2██████',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'text labels and custom values' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromBars(
                    Bar::fromValue(1)->textValue('A')->label(Line::fromString('X0')),
                    Bar::fromValue(2)->textValue('B')->label(Line::fromString('X1')),
                )
            )->direction(Direction::Horizontal),
            [
                'X0 A██    ',
                '          ',
                'X1 B██████',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'with style' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromBars(
                    Bar::fromValue(1)->textValue('A')->label(Line::fromString('X0'))->style(Style::default()),
                    Bar::fromValue(2)->textValue('B')->label(Line::fromString('X1')),
                )
            )->direction(Direction::Horizontal),
            [
                'X0 A██    ',
                '          ',
                'X1 B██████',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'wider than dimensions' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromBars(
                    Bar::fromValue(1)->textValue('A')->label(Line::fromString('X0'))->style(Style::default()),
                    Bar::fromValue(2)->textValue('B')->label(Line::fromString('X1')),
                )
            )->direction(Direction::Horizontal)->barGap(10),
            [
                'X0 A██    ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'taller than dimensions' => [
            Area::fromDimensions(10, 5),
            BarChartWidget::default()->data(
                BarGroup::fromBars(
                    Bar::fromValue(1)->textValue('A')->label(Line::fromString('X0'))->style(Style::default()),
                    Bar::fromValue(2)->textValue('B')->label(Line::fromString('X1')),
                )
            )->direction(Direction::Vertical)->barGap(10),
            [
                '          ',
                '▄         ',
                '█         ',
                'A         ',
                'X         ',
            ]
           ,
        ];
    }
}
