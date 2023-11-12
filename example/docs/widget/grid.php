<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Grid;

require 'vendor/autoload.php';

$display = DisplayBuilder::new(PhpTermBackend::new())->build();
$display->drawWidget(
    Grid::default()
        ->direction(Direction::Horizontal)
        ->constraints(
            Constraint::percentage(50),
            Constraint::percentage(50),
        )
        ->widgets(
            Block::default()->borders(Borders::ALL)->titles(Title::fromString('Left')),
            Grid::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->widgets(
                    Block::default()->borders(Borders::ALL)->titles(Title::fromString('Top Right')),
                    Block::default()->borders(Borders::ALL)->titles(Title::fromString('Bottom Right')),
                )
        )
);
