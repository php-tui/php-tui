<?php

use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::default()
        ->xBounds(AxisBounds::new(0, 320))
        ->yBounds(AxisBounds::new(0, 240))
        ->marker(Marker::HalfBlock)
        ->paint(function (CanvasContext $context): void {

            $context->draw(
                // this is expensive, don't do this on each frame if you are
                // animating!
                ImageShape::fromFilename(__DIR__ . '/example.jpg')
            );

        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
