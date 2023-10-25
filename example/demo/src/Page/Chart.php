<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Chart\Axis;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Widget\Chart\DataSet;
use PhpTui\Tui\Model\Widget\Span;

class Chart implements Component
{
    public function build(): Widget
    {
        $xLabels = [
            Span::styled('one', Style::default()),
            Span::styled('two', Style::default()),
            Span::styled('three', Style::default()),
        ];
        $dataSets = [
            DataSet::new('data1')
                ->marker(Marker::Dot)
                ->style(Style::default()->fg(AnsiColor::Cyan))
                ->data($this->sinData(0)),
            DataSet::new('data1')
                ->marker(Marker::Braille)
                ->style(Style::default()->fg(AnsiColor::Yellow))
                ->data($this->sinData(90)),
        ];

        $chart1 = Chart::new($dataSets)
            ->block(
                Block::default()
                    ->title(Title::fromLine(Line::fromString('Chart 1')))
                    ->borders(Borders::ALL)
            )
            ->xAxis(
                Axis::default()
                    //->title('X Axis')
                    ->style(Style::default()->fg(AnsiColor::Gray))
                ->labels($xLabels)
                ->bounds(AxisBounds::new(0, 400))
            )
            ->yAxis(
                Axis::default()
                    //->title('X Axis')
                ->style(Style::default()->fg(AnsiColor::Gray))
                ->labels([
                    Span::fromString('-20'),
                    Span::fromString('0'),
                    Span::fromString('20'),
                ])
                ->bounds(AxisBounds::new(-400,400))
            );
    }

    public function handle(Event $event): void
    {
    }
}
