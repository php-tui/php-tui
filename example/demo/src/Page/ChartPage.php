<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\Axis;
use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Extension\Core\Widget\Chart\DataSet;
use PhpTui\Tui\Extension\Core\Widget\ChartWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;
use PhpTui\Tui\Widget\Widget;

final class ChartPage implements Component
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
        $dataSets1 = [
            DataSet::new('data1')
                ->marker(Marker::Dot)
                ->style(Style::default()->cyan())
                ->data($this->sinData(0)),
            DataSet::new('data2')
                ->marker(Marker::Braille)
                ->style(Style::default()->yellow())
                ->data($this->sinData(90)),
        ];
        $dataSets2 = [
            DataSet::new('data1')
                ->marker(Marker::HalfBlock)
                ->style(Style::default()->green())
                ->data($this->sinData(45)),
            DataSet::new('data2')
                ->marker(Marker::Block)
                ->style(Style::default()->blue())
                ->data($this->sinData(135)),
        ];

        return GridWidget::default()
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                BlockWidget::default()
                    ->titles(Title::fromLine(Line::fromString('Chart 1')))
                    ->borders(Borders::ALL)
                    ->widget(
                        ChartWidget::new(...$dataSets1)
                            ->xAxis(
                                Axis::default()
                                    //->title('X Axis')
                                    ->style(Style::default()->gray())
                                ->labels(...$xLabels)
                                ->bounds(AxisBounds::new(0, 400))
                            )
                            ->yAxis(
                                Axis::default()
                                    //->title('X Axis')
                                ->style(Style::default()->gray())
                                ->labels(
                                    Span::fromString('-20'),
                                    Span::fromString('0'),
                                    Span::fromString('20'),
                                )
                                ->bounds(AxisBounds::new(-400, 400))
                            )
                    ),
                BlockWidget::default()
                    ->titles(Title::fromLine(Line::fromString('Chart 2')))
                    ->borders(Borders::ALL)
                    ->widget(
                        ChartWidget::new(...$dataSets2)
                            ->xAxis(
                                Axis::default()
                                    ->style(Style::default()->gray())
                                ->labels(...$xLabels)
                                ->bounds(AxisBounds::new(0, 400))
                            )
                            ->yAxis(
                                Axis::default()
                                    //->title('X Axis')
                                ->style(Style::default()->gray())
                                ->labels(
                                    Span::fromString('-20'),
                                    Span::fromString('0'),
                                    Span::fromString('20'),
                                )
                                ->bounds(AxisBounds::new(-400, 400))
                            )
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
