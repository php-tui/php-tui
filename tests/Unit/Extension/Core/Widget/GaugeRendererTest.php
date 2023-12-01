<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Widget\Widget;

final class GaugeRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideGaugeRender
     * @param array<int,string> $expected
     */
    public function testGaugeRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function provideGaugeRender(): Generator
    {
        yield '0' => [
            Area::fromDimensions(10, 1),
            GaugeWidget::default()->ratio(0),
            [
                '  0.00%   ',
            ]
           ,
        ];
        yield '50' => [
            Area::fromDimensions(10, 4),
            GaugeWidget::default()->ratio(0.5),
            [
                '█████     ',
                '█████     ',
                '██50.00%  ',
                '█████     ',
            ]
           ,
        ];
        yield 'fi' => [
            Area::fromDimensions(10, 1),
            GaugeWidget::default()->ratio(0.98),
            [
                '██98.00%█▊',
            ]
           ,
        ];
        yield '75' => [
            Area::fromDimensions(10, 4),
            GaugeWidget::default()->ratio(0.75),
            [
                '███████▌  ',
                '███████▌  ',
                '██75.00%  ',
                '███████▌  ',
            ]
           ,
        ];
        yield 'custom label' => [
            Area::fromDimensions(10, 3),
            GaugeWidget::default()->ratio(1)->label(Span::fromString('Hello')),
            [
                '██████████',
                '██Hello███',
                '██████████',
            ]
           ,
        ];
    }
}
