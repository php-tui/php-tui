<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape\Sprite;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Canvas::default()
        ->xBounds(AxisBounds::new(0, 20))
        ->yBounds(AxisBounds::new(0, 10))
        ->marker(Marker::Braille)
        ->paint(function (CanvasContext $context): void {

            $context->draw(
                new Sprite(
                    rows: [
                        ' XXX ',
                        'X   X ',
                        'X   X ',
                        ' XXX ',
                        '  X',
                        'XXXXX',
                        '  X       ', // rows do not need
                        '  X       ', // equals numbers of chars
                        ' X X     ',
                        'X   X    ',
                    ],
                    color: AnsiColor::Blue,
                    density: 4,
                    position: FloatPosition::at(0, 0),
                )
            );

        })
        ->render($buffer->area(), $buffer);
});
$display->flush();
