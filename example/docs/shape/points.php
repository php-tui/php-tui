<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Shape\Points;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->drawWidget(
    Canvas::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->draw(
            Points::new([
                [0, 0],
                [2, 2],
                [4, 4],
                [6, 6],
                [8, 8],
            ], AnsiColor::Black)
        )
);
