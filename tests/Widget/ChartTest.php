<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Cell;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Widget\Chart;
use DTL\PhpTui\Widget\Chart\DataSet;
use PHPUnit\Framework\TestCase;

class ChartTest extends TestCase
{
    public function testRender(): void
    {
        $chart = Chart::new([
            DataSet::new('data1')
                ->marker(Marker::Dot)
                ->style(Style::default()->fg(AnsiColor::Green))
                ->data(
                    array_map(function (int $x, int $y) {
                        return [$x, $y];
                    }, range(0, 7), [0, 1, 2, 1, 0, -1, -2, -1])
                )
        ]);
        
        $area = Area::fromDimensions(8, 5);
        $buffer = Buffer::empty($area);
        $chart->render($area, $buffer);
        self::assertEquals(
            [
                '  •     ',
                ' • •    ',
                '•   •   ',
                '     • •',
                '      • ',
            ],
            $buffer->toLines()
        );
        
    }
}
