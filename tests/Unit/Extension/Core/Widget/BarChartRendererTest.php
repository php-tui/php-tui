<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\BarChart\Bar;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Widget\Direction;
use PhpTui\Tui\Widget\Widget;

final class BarChartRendererTest extends WidgetTestCase
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
                /** @phpstan-ignore-next-line */
                BarGroup::fromArray(['B0' => -1, 'B1' => 2])
            ),
            [
                '  █       ',
                '  █       ',
                '  █       ',
                '  2       ',
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
        yield 'horizontal chart with width > 1' => [
            Area::fromDimensions(10, 10),
            BarChartWidget::default()->barWidth(4)->data(
                BarGroup::fromBars(
                    Bar::fromValue(1)->textValue('A')->label(Line::fromString('X0'))->style(Style::default()),
                    Bar::fromValue(2)->textValue('B')->label(Line::fromString('X1')),
                ),
            )->direction(Direction::Horizontal),
            [
                '   ███    ',
                '   ███    ',
                'X0 A██    ',
                '   ███    ',
                '          ',
                '   ███████',
                '   ███████',
                'X1 B██████',
                '   ███████',
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
