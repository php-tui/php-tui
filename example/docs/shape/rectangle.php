<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\Rectangle;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
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
