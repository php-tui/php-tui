<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Widget\Borders;

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
