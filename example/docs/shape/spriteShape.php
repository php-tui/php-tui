<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\SpriteShape;
use PhpTui\Tui\Extension\Core\Widget\Canvas\Marker;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Position\FloatPosition;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CanvasWidget::fromIntBounds(0, 30, 0, 15)
        ->marker(Marker::Block)
        ->draw(
            new SpriteShape(
                rows: [
                    ' XXX ',
                    'X   X ',
                    'X   X ',
                    ' XXX ',
                    '  X',
                    'XXXXX',
                    '  X       ', // rows do not need
                    '  X       ', // equals numbers of chars
                    ' X X     ',
                    'X   X    ',
                ],
                color: AnsiColor::White,
                position: FloatPosition::at(2, 2),
            )
        )
);
