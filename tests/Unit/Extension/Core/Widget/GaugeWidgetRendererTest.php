<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;

class GaugeWidgetRendererTest extends WidgetTestCase
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
        yield 'write to buffer' => [
            Area::fromDimensions(10, 2),
            GaugeWidget::default()->ratio(0.5),
            [
                'xxxxx     ',
                '          ',
            ]
           ,
        ];
    }
}

