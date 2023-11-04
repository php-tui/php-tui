<?php

use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;

require __DIR__ .'/../../../vendor/autoload.php';

// create the font registry
// this is EXPENSIVE to create, only do it once!
$registry = FontRegistry::default();

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer) use ($registry): void {
    Canvas::fromIntBounds(0, 50, 0, 20)
        ->marker(Marker::Block)
        ->paint(function (CanvasContext $context) use ($registry) : void {

            $context->draw(
                new TextShape(
                    font: $registry->get('default'),
                    text: 'Hello!',
                    color: AnsiColor::Green,
                    position: FloatPosition::at(10, 7),
                ),
            );

        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
