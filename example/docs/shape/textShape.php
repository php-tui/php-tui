<?php

use PhpTui\Tui\Adapter\Bdf\BdfShapeSet;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Widget\Canvas;

require 'vendor/autoload.php';

// create the font registry
// this is EXPENSIVE to create, only do it once!
$registry = FontRegistry::default();

$display = DisplayBuilder::new()
    ->addShapeSet(new BdfShapeSet(FontRegistry::default()))
    ->build();

$display->drawWidget(
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
