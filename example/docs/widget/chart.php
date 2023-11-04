<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Widget\Chart;
use PhpTui\Tui\Widget\Chart\Axis;
use PhpTui\Tui\Widget\Chart\DataSet;
use PhpTui\Tui\Widget\Chart\GraphType;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Chart::new(
        DataSet::new('Ships')
                ->data(
                    [[0, -2], [1, -1], [2, 0], [3, 1], [4, 2], [5, 1], [6, 0], [7, -1]]
                )
                ->marker(Marker::Dot),
        DataSet::new('Birds')
                ->data(
                    [[0, 2], [1, 1], [2, 0], [3, -1], [4, -2], [5, -1], [6, 0], [7, 1]]
                )
                ->graphType(GraphType::Line)
                ->marker(Marker::Braille),
    )
        ->xAxis(
            Axis::default()
                ->labels([
                    Span::fromString('Good'),
                    Span::fromString('Neutral'),
                    Span::fromString('Bad'),
                ])
                ->bounds(AxisBounds::new(0, 8))
        )
        ->yAxis(
            Axis::default()
                ->labels([
                    Span::fromString('Profit'),
                    Span::fromString('Neutral'),
                    Span::fromString('Loss')
                ])
                ->bounds(AxisBounds::new(-4, 4))
        )
        ->render($buffer->area(), $buffer);
});
