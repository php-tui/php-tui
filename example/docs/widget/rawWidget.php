<?php

declare(strict_types=1);

use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Position\Position;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    RawWidget::new(function (Buffer $buffer): void {
        $buffer->putString(Position::at(10, 10), 'Hello World');
    })
);
