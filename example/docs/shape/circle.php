<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Shape\Circle;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->drawWidget(
    Canvas::fromIntBounds(-1, 21, -1, 21)
        ->marker(Marker::Dot)
        ->draw(Circle::fromScalars(10, 10, 10)->color(AnsiColor::Green))
);
