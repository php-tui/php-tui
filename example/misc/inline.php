<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->inline(10)->build();
$display->draw(
    Block::default()->borders(Borders::ALL)
    ->widget(
        Paragraph::fromString('Hello')->style(Style::default()->fg(AnsiColor::Red))
    )
);
