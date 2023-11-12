<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Rectangle;

require 'vendor/autoload.php';

$display = DisplayBuilder::new(PhpTermBackend::new())->build();
$display->drawWidget(
    Canvas::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->draw(
            Rectangle::fromScalars(
                0,
                0,
                10,
                10,
            )->color(
                AnsiColor::Green
            )
        )
);
