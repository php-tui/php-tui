<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Sprite;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->drawWidget(
    Canvas::fromIntBounds(0, 20, 0, 10)
        ->marker(Marker::Braille)
        ->draw(
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
        )
);
