<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Circle;
use PhpTui\Tui\Widget\Canvas\Shape\Line;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::default()
        ->xBounds(AxisBounds::new(0, 20))
        ->yBounds(AxisBounds::new(0, 20))
        ->marker(Marker::Dot)
        ->paint(function (CanvasContext $context): void {

            $context->draw(Line::fromPrimitives(
                0,  // x1
                0,  // y1
                10, // x2
                10, // y2
                AnsiColor::Green
            ));
        })
        ->render($buffer->area(), $buffer);
});
$display->flush();

