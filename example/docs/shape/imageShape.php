<?php

use PhpTui\Tui\Adapter\ImageMagick\ImageMagickShapeSet;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Widget\Canvas;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()
    ->addShapeSet(new ImageMagickShapeSet())
    ->build();
$display->drawWidget(
    Canvas::fromIntBounds(0, 320, 0, 240)
        ->marker(Marker::HalfBlock)
        ->draw(
            ImageShape::fromPath(__DIR__ . '/example.jpg')
        )
);
