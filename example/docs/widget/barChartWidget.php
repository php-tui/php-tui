<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    BarChartWidget::default()
        ->barWidth(10)
        ->barStyle(Style::default()->red())
        ->groupGap(5)
        ->data(
            BarGroup::fromArray([
                '1' => 12,
                '2' => 15,
                '3' => 13,
            ])->label(Line::fromString('md5')),
            BarGroup::fromArray([
                '1' => 22,
                '2' => 15,
                '3' => 23,
            ])->label(Line::fromString('sha256')),
        )
);
