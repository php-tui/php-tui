<?php

declare(strict_types=1);

use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Terminal;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Extension\Core\Widget\Grid;
use PhpTui\Tui\Model\Constraint;

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
$display = DisplayBuilder::default()->inline(8)->build();
$downloads = [
    new Download(100),
    new Download(200),
    new Download(400),
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
                    fn (Download $download) => GaugeWidget::default()->ratio($download->ratio()),
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
