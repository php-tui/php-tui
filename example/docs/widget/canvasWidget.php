<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\CircleShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Canvas\Marker;
use PhpTui\Tui\Model\Color\AnsiColor;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(-1, 21, -1, 21)
        // the marker determines both the effective resolution of
        // the canvas and the "mark" that is made
        ->marker(Marker::Dot)

        // note can use `$canvas->draw($shape, ...)` without the closure for
        // most cases
        ->paint(function (CanvasContext $context): void {

            $context->draw(CircleShape::fromScalars(10, 10, 10)->color(AnsiColor::Green));
        })
);
