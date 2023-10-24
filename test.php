<?php

use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Terminal as PhpTuiTerminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Display;

require './vendor/autoload.php';

$backend = PhpTermBackend::new(PhpTuiTerminal::new());
$terminal = Display::fullscreen($backend);
$terminal->enableRawMode();
$terminal->disableRawMode();
foreach ($backend->events(1000) as $event) {
    if ($event instanceof CodedKeyEvent) {
        if ($event->code === KeyCode::Esc) {
            echo 'Quitting!';
            break;
        }
    }
}
