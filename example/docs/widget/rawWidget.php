<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Borders;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    BufferWidget::new(function (BufferContext $context): void {
        $context->draw(BlockWidget::default()->borders(Borders::ALL));
        $context->buffer->putString(Position::at(10, 10), 'Hello World');
    })
);
