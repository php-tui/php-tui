<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\Axis;
use PhpTui\Tui\Extension\Core\Widget\Chart\DataSet;
use PhpTui\Tui\Extension\Core\Widget\ChartWidget;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;

class ChartPage implements Component
{
    private int $tick = 0;

    public function build(): Widget
    {
        $this->tick++;
        $xLabels = [
            Span::styled('one', Style::default()),
            Span::styled('two', Style::default()),
            Span::styled('three', Style::default()),
        ];
        $dataSets = [
            DataSet::new('data1')
                ->marker(Marker::Dot)
                ->style(Style::default()->cyan())
                ->data($this->sinData(0)),
            DataSet::new('data1')
                ->marker(Marker::Braille)
                ->style(Style::default()->yellow())
                ->data($this->sinData(90)),
        ];

        return BlockWidget::default()
            ->titles(Title::fromLine(Line::fromString('Chart 1')))
            ->borders(Borders::ALL)
            ->widget(
                ChartWidget::new(...$dataSets)
                    ->xAxis(
                        Axis::default()
                            //->title('X Axis')
                            ->style(Style::default()->gray())
                        ->labels($xLabels)
                        ->bounds(AxisBounds::new(0, 400))
                    )
                    ->yAxis(
                        Axis::default()
                            //->title('X Axis')
                        ->style(Style::default()->gray())
                        ->labels([
                            Span::fromString('-20'),
                            Span::fromString('0'),
                            Span::fromString('20'),
                        ])
                        ->bounds(AxisBounds::new(-400, 400))
                    )
            );
    }

    public function handle(Event $event): void
    {
    }

    /**
     * @return array<int,array{float,float}>
     */
    private function sinData(int $offset): array
    {
        $data = [];
        for ($i = 0; $i < 400; $i++) {
            $point = (int) (sin(
                ($this->tick + $i + $offset) % 360 / 10
            ) * 400);
            $data[] = [$i, $point];
        }

        return $data;
    }
}
