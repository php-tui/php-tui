<?php

declare(strict_types=1);

use PhpTui\Term\Event\CursorPositionEvent;
use PhpTui\Term\EventProvider\ArrayEventProvider;
use PhpTui\Term\RawMode\TestRawMode;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Borders;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Title;

require 'vendor/autoload.php';

$title = '';

// -----
// ignore this! it is to enable this to work in "headless" mode for the tests.
$backend = PhpTermBackend::new(Terminal::new(
    eventProvider: ArrayEventProvider::fromEvents(new CursorPositionEvent(0, 0), new CursorPositionEvent(0, 0)),
    rawMode: new TestRawMode(),
));
// -----

$display = DisplayBuilder::default($backend)->inline(20)->build();
$widget = fn (string $title) => GridWidget::default()
        ->constraints(Constraint::length(20))

        ->widgets(
            BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString($title))
        );

for ($i = 0; $i < 5; $i++) {
    for ($ii = 0; $ii < 5; $ii++) {
        $display->draw($widget((string)$ii));
    }

    // insert _before_ the viewport, moving the cursor position down
    $display->insertBefore(20, $widget('done'));
}
$display->draw($widget('done'));
