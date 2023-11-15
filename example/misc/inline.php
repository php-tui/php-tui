<?php

declare(strict_types=1);

use PhpTui\Term\Actions;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Terminal;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Extension\Core\Widget\Grid;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Span;

require 'vendor/autoload.php';

class Download
{
    public function __construct(
        public int $size,
        public float $downloaded = 0.0,
    ) {
    }

    public function ratio(): float
    {
        return $this->downloaded / $this->size;
    }
}
$terminal = Terminal::new();
$terminal->execute(Actions::cursorHide());
$display = DisplayBuilder::default()->inline(8)->build();
$downloads = [
    new Download(100),
    new Download(400),
    new Download(200),
    new Download(800),
];
while ($downloads) {
    while (null !== $event = $terminal->events()->next()) {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Esc) {
                break 2;
            }
        }
    }
    $display->draw(
        Grid::default()
            ->constraints(
                ...array_map(fn () => Constraint::length(1), $downloads),
                ...[Constraint::min(0)],
            )
            ->widgets(
                ...array_map(
                    function (Download $download) {
                        return Grid::default()
                            ->direction(Direction::Horizontal)
                            ->constraints(
                                Constraint::percentage(30),
                                Constraint::percentage(70),
                            )
                            ->widgets(
                                Paragraph::fromSpans(
                                    Span::fromString('Downloaded')->style(
                                        Style::default()->fg(AnsiColor::Green)
                                    ),
                                    Span::fromString(sprintf(
                                        ' %s bytes', $download->downloaded,
                                    ))->style(
                                        Style::default()->fg(AnsiColor::White)
                                    )
                                ),
                                GaugeWidget::default()
                                    ->ratio($download->ratio())
                                    ->style(Style::default()->fg(AnsiColor::Yellow))
                            );
                    },
                    $downloads
                )
            )
    );
    foreach ($downloads as $index => $download) {
        $download->downloaded += rand(0, 100) / 100;
        if ($download->downloaded > $download->size) {
            $download->downloaded = $download->size;
            unset($downloads[$index]);
        }
    }
    usleep(1_000);
}
$terminal->execute(Actions::cursorShow());
