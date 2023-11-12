<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Shape\Map;
use PhpTui\Tui\Shape\MapResolution;

require 'vendor/autoload.php';

$display = DisplayBuilder::new()->build();
$display->drawWidget(
    Canvas::fromIntBounds(-180, 180, -90, 90)
        ->paint(function (CanvasContext $ctx): void {
            $ctx->draw(Map::default()->resolution(MapResolution::High));
        })
);
