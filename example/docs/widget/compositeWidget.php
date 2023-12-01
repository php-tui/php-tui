<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CompositeWidget;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    CompositeWidget::fromWidgets(
        BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Window 1')),
        ScrollbarWidget::default()->state(new ScrollbarState(20, 5, 5)),
        ScrollbarWidget::default()
            ->state(new ScrollbarState(20, 5, 5))
            ->orientation(ScrollbarOrientation::VerticalRight),
    )
);
