<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Canvas\CanvasContext;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(-180, 180, -90, 90)
        ->paint(function (CanvasContext $ctx): void {
            $ctx->draw(MapShape::default()->resolution(MapResolution::High));
        })
);
