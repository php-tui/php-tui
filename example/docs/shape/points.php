<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Points;

require __DIR__ .'/../../../vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->paint(function (CanvasContext $context): void {

            $context->draw(
                Points::new([
                    [0, 0],
                    [2, 2],
                    [4, 4],
                    [6, 6],
                    [8, 8],
                ], AnsiColor::Black)
            );
        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
