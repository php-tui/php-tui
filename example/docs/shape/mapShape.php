<?php

declare(strict_types=1);

use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(-180, 180, -90, 90)
        ->marker(Marker::Braille)
        ->draw(
            MapShape::default()
                ->resolution(MapResolution::High)
                ->color(AnsiColor::Green)
        )
);
