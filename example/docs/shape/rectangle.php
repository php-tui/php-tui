<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Shape\Rectangle;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->draw(
            Rectangle::fromPrimitives(
                0,
                0,
                10,
                10,
                AnsiColor::Green
            )
        )
        ->render($buffer->area(), $buffer);
});
$display->flush();
