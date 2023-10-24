<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Adapter\Symfony\SymfonyBackend;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Chart;
use PhpTui\Tui\Widget\Chart\Axis;
use PhpTui\Tui\Widget\Chart\DataSet;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__ . '/../vendor/autoload.php';

class App {
    private function __construct(
        private int $tick,
    )
    {
    }

    public static function run(): self
    {
        $app = new self(
            0
        );
        $cursor = new Cursor(new ConsoleOutput());
        $cursor->hide();
        $cursor->clearScreen();
        $backend = PhpTermBackend::new();
        $terminal = Display::fullscreen($backend);
        while (true) {
            $terminal->draw(function (Buffer $buffer) use ($app): void {
                $app->ui($buffer);
            });
            usleep(1000);
            $app->onTick();
        }
    }

    private function ui(Buffer $buffer): void
    {
        $size = $buffer->area();
        $chunks  = Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::percentage(33),
                Constraint::percentage(33),
                Constraint::percentage(33),
            ])
            ->split($size);
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

        $chart1->render($chunks->get(0), $buffer);
    }

    private function onTick(): void
    {
        $this->tick++;
    }

    private function sinData(int $offset): array
    {
        $data = [];
        for ($i = 0; $i < 400; $i++) {
            $point = intval(sin(
                ($this->tick + $i + $offset) % 360 / 10
            ) * 400);
            $data[] = [$i, $point];
        }
        return $data;
    }

}

App::run();
