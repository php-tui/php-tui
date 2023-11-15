<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Bdf\BdfExtension;
use PhpTui\Tui\Extension\Bdf\FontRegistry;
use PhpTui\Tui\Extension\Bdf\Shape\TextShape;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Extension\Core\Widget\Canvas;

require 'vendor/autoload.php';

// create the font registry
// this is EXPENSIVE to create, only do it once!
$registry = FontRegistry::default();

$display = DisplayBuilder::default()
    ->addExtension(new BdfExtension())
    ->build();

$display->draw(
    Canvas::fromIntBounds(0, 50, 0, 20)
        ->marker(Marker::Block)
        ->draw(
            new TextShape(
                font: 'default',
                text: 'Hello!',
                color: AnsiColor::Green,
                position: FloatPosition::at(10, 7),
            ),
        )
);
