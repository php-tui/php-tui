<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Extension\Core\Shape\Map;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    Canvas::fromIntBounds(-180, 180, -90, 90)
        ->paint(function (CanvasContext $ctx): void {
            $ctx->draw(Map::default()->resolution(MapResolution::High));
        })
);
