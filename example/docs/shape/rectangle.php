<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Points;
use PhpTui\Tui\Widget\Canvas\Shape\Rectangle;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::default()
        ->xBounds(AxisBounds::new(0, 10))
        ->yBounds(AxisBounds::new(0, 10))
        ->marker(Marker::Dot)
        ->paint(function (CanvasContext $context): void {

            $context->draw(
                Rectangle::fromPrimitives(
                    0,
                    0,
                    10,
                    10,
                    AnsiColor::Green
                )
            );

        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
