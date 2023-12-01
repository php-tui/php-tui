<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Model\Color\RgbColor;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Widget\Borders;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    BlockWidget::default()
        ->borders(Borders::ALL)
        ->style(
            Style::default()
                ->fg(RgbColor::fromRgb(255, 0, 0))
                ->bg(RgbColor::fromRgb(0, 0, 255))
        )
);
