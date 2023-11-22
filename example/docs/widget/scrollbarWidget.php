<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    ScrollbarWidget::default()
        ->state(new ScrollbarState(30, 15, 5))
);
