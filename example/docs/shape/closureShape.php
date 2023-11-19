<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\ClosureShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Text\Line;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(-1, 21, -1, 21)
        ->marker(Marker::Dot)
        ->draw(
            new ClosureShape(
                function (Painter $painter): void {
                    $painter->context->print(
                        0,
                        0,
                        Line::fromString('Hello World')
                    );
                }
            )
        )
);
