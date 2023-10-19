<?php

use DTL\PhpTui\Adapter\Symfony\SymfonyBackend;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Terminal;
use DTL\PhpTui\Model\Widget\Borders;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Model\Widget\Title;
use DTL\PhpTui\Widget\Block;
use DTL\PhpTui\Widget\Chart;
use DTL\PhpTui\Widget\Chart\Axis;
use DTL\PhpTui\Widget\Chart\DataSet;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__ . '/../vendor/autoload.php';

class App {
    private function __construct(
        private array $data1,
        private array $data2,
        private AxisBounds $window,
    )
    {
    }

    public static function run(): self
    {
        $app = new self(
            data1: array_map(function (int $x, int $y) {
                return [$x, $y];
            }, range(0, 128), range(0, 128)),
            data2: array_map(function (int $x, int $y) {
                return [$x, $y];
            }, range(0, 128), range(128, 0)),
            window: new AxisBounds(0, 128),
        );
        $cursor = new Cursor(new ConsoleOutput());
        $cursor->hide();
        $cursor->clearScreen();
        $backend = SymfonyBackend::new();
        $terminal = Terminal::fullscreen($backend);
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
                ->data($this->data1),
            DataSet::new('data1')
                ->marker(Marker::Braille)
                ->style(Style::default()->fg(AnsiColor::Yellow))
                ->data($this->data2),
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
                ->bounds($this->window)
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
                ->bounds($this->window)
            );

        $chart1->render($chunks->get(0), $buffer);
    }

    private function onTick(): void
    {
    }

}

App::run();
