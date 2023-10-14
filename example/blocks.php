<?php

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend\DummyBackend;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Terminal;

require_once __DIR__ . '/../vendor/autoload.php';

$backend = new DummyBackend(128, 33);
$terminal = Terminal::fullscreen($backend);
$terminal->draw(function (Buffer $buffer) {
    $area = $buffer->area();
    $layout = Layout::default()
        ->direction(Direction::Vertical)
        ->constraints([
            Constraint::length(1),
            Constraint::min(10)
        ])
        ->split($area);
    $titleArea = $layout->get(0);
    dump('Done:',$titleArea);
});
