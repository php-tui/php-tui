<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Circle;

require 'vendor/autoload.php';

$display = DisplayBuilder::new()->build();
$display->drawWidget(
    Canvas::fromIntBounds(-1, 21, -1, 21)
        ->marker(Marker::Dot)
        ->draw(Circle::fromScalars(10, 10, 10)->color(AnsiColor::Green))
);
