<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Widget\Chart;
use DTL\PhpTui\Widget\Chart\Axis;
use DTL\PhpTui\Widget\Chart\DataSet;
use PHPUnit\Framework\TestCase;

class ChartTest extends TestCase
{
    public function testRender(): void
    {
        $chart = Chart::new(
            [
                DataSet::new('data1')
                    ->marker(Marker::Dot)
                    ->style(Style::default()->fg(AnsiColor::Green))
                    ->data(
                        array_map(function (int $x, int $y) {
                            return [$x, $y];
                        }, range(0, 7), [0, 1, 2, 1, 0, -1, -2, -1])
                    )
            ]
        )->xAxis(
            Axis::default()->bounds(AxisBounds::new(0, 7))
        )->yAxis(
            Axis::default()->bounds(AxisBounds::new(-2, 2))
        );

        self::assertEquals(
            [
                '  •     ',
                ' • •    ',
                '•   •   ',
                '     • •',
                '      • ',
            ],
            $this->render($chart)
        );
    }

    public function testRenderAxisLabels(): void
    {
        $chart = Chart::new(
            [
                DataSet::new('data1')
                    ->marker(Marker::Dot)
                    ->style(Style::default()->fg(AnsiColor::Green))
                    ->data(
                        array_map(function (int $x, int $y) {
                            return [$x, $y];
                        }, range(0, 7), [0, 1, 2, 1, 0, -1, -2, -1])
                    )
            ]
        )->xAxis(
            Axis::default()->bounds(AxisBounds::new(0, 7))->labels([Span::fromString('1'), Span::fromString('2')])
        )->yAxis(
            Axis::default()->bounds(AxisBounds::new(-2, 2))->labels([Span::fromString('1'), Span::fromString('2')])
        );

        self::assertEquals(
            [
                '  •     ',
                ' • •    ',
                ' •  •   ',
                '     • •',
                '      • ',
                '────────',
            ],
            $this->render($chart, 8, 6)
        );
    }

    /**
     * @return string[]
     */
    private function render(Chart $chart, int $width = 8, int $height = 5): array
    {
        $area = Area::fromDimensions($width, $height);
        $buffer = Buffer::empty($area);
        $chart->render($area, $buffer);
        return $buffer->toLines();
    }
}

