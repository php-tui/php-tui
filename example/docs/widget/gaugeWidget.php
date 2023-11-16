<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    GaugeWidget::default()->ratio(0.25)->style(Style::default()->fg(AnsiColor::Yellow))
);
