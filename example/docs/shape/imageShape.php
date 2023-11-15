<?php

use PhpTui\Tui\Adapter\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Extension\Core\Widget\Canvas;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()
    ->addExtension(new ImageMagickExtension())
    ->build();
$display->draw(
    Canvas::fromIntBounds(0, 320, 0, 240)
        ->marker(Marker::HalfBlock)
        ->draw(
            ImageShape::fromPath(__DIR__ . '/example.jpg')
        )
);
