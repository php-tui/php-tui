<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\Grid;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Title;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
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
