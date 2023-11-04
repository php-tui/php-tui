<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Map;
use PhpTui\Tui\Widget\Canvas\Shape\MapResolution;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::fromIntBounds(-180, 180, -90, 90)
        ->marker(Marker::Braille)
        ->paint(function (CanvasContext $context): void {

            $context->draw(
                Map::default()->resolution(MapResolution::High)->color(AnsiColor::Green)
            );
        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
