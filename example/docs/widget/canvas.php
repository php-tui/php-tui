<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Shape\Circle;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->drawWidget(
    Canvas::fromIntBounds(-1, 21, -1, 21)
        // the marker determines both the effective resolution of
        // the canvas and the "mark" that is made
        ->marker(Marker::Dot)

        // note can use `$canvas->draw($shape, ...)` without the closure for
        // most cases
        ->paint(function (CanvasContext $context): void {

            $context->draw(Circle::fromScalars(10, 10, 10)->color(AnsiColor::Green));
        })
);
