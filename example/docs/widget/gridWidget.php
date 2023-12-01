<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Borders;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Title;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    GridWidget::default()
        ->direction(Direction::Horizontal)
        ->constraints(
            Constraint::percentage(50),
            Constraint::percentage(50),
        )
        ->widgets(
            BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Left')),
            GridWidget::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->widgets(
                    BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Top Right')),
                    BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Bottom Right')),
                )
        )
);
