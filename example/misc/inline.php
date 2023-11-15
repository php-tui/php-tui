<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Borders;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->inline(10)->build();
$display->draw(
    Block::default()->borders(Borders::ALL)
    ->widget(
        Paragraph::fromString('Hello')->style(Style::default()->fg(AnsiColor::Red))
    )
);
