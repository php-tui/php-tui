<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\PointsShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(0, 10, 0, 10)
        ->marker(Marker::Dot)
        ->draw(
            PointsShape::new([
                [0, 0],
                [2, 2],
                [4, 4],
                [6, 6],
                [8, 8],
            ], AnsiColor::Cyan)
        )
);
