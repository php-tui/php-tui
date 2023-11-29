<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\TabsWidget;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    TabsWidget::default()
        ->titles(
            Line::fromString('Tab 0'),
            Line::fromString('Tab 1'),
            Line::fromString('Tab 3'),
        )
        ->select(0)
        ->highlightStyle(Style::default()->white()->onRed())
        ->divider(Span::fromString('|'))
);
