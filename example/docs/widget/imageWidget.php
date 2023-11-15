<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Extension\ImageMagick\Widget\ImageWidget;
use PhpTui\Tui\Model\Marker;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()
    ->addExtension(new ImageMagickExtension())
    ->build();
$display->draw(
    new ImageWidget(path: __DIR__ . '/../shape/example.jpg'),
);
