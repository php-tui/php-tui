<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\LineShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Canvas\Marker;
use PhpTui\Tui\Model\Color\AnsiColor;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(0, 20, 0, 20)
        ->marker(Marker::Dot)
        ->draw(LineShape::fromScalars(
            0,  // x1
            0,  // y1
            20, // x2
            20, // y2
        )->color(AnsiColor::Green))
);
