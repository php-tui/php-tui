<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()
    ->addExtension(new ImageMagickExtension())
    ->build();
$display->draw(
    CanvasWidget::fromIntBounds(0, 320, 0, 240)
        ->marker(Marker::HalfBlock)
        ->draw(
            ImageShape::fromPath(__DIR__ . '/example.jpg')
        )
);
