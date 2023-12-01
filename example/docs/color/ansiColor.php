<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Model\Borders;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Style\Style;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    BlockWidget::default()
        ->borders(Borders::ALL)
        ->style(
            Style::default()
                ->fg(AnsiColor::Blue)
                ->bg(AnsiColor::Red)
        )
);
