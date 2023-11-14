<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Sprite;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    Canvas::fromIntBounds(0, 30, 0, 15)
        ->marker(Marker::Block)
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
                color: AnsiColor::White,
                density: 2,
                position: FloatPosition::at(2, 2),
            )
        )
);
