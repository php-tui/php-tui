<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Position\Position;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    RawWidget::new(function (Buffer $buffer): void {
        $buffer->putString(Position::at(10, 10), 'Hello World');
    })
);
