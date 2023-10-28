<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Map;
use PhpTui\Tui\Widget\Canvas\Shape\MapResolution;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::default()
        ->xBounds(AxisBounds::new(-180, 180))
        ->yBounds(AxisBounds::new(-90, 90))
        ->paint(function (CanvasContext $ctx): void {
            $ctx->draw(Map::default()->resolution(MapResolution::High));
        })->render($buffer->area(), $buffer);
});
$display->flush();
