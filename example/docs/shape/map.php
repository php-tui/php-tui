<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Map;
use PhpTui\Tui\Shape\MapResolution;

require 'vendor/autoload.php';

$display = DisplayBuilder::new(PhpTermBackend::new())->build();
$display->drawWidget(
    Canvas::fromIntBounds(-180, 180, -90, 90)
        ->marker(Marker::Braille)
        ->draw(
            Map::default()
                ->resolution(MapResolution::High)
                ->color(AnsiColor::Green)
        )
);
