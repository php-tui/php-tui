<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\RectangleShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->draw(
            RectangleShape::fromScalars(
                0,
                0,
                10,
                10,
            )->color(
                AnsiColor::Green
            )
        )
);
