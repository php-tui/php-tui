<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Color\LinearGradient;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Style\Style;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    GaugeWidget::default()
        ->ratio(1)
        ->style(
            Style::default()
                ->fg(
                    LinearGradient::from(RgbColor::fromHex('#ffaaaa'))
                        ->addStop(0.5, RgbColor::fromHex('#aaffaa'))
                        ->addStop(1, RgbColor::fromHex('#aaaaff'))
                )
        )
);
