<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    Block::default()
        ->borders(Borders::ALL)
        ->titles(Title::fromString('Hello World'))
        ->borderType(BorderType::Rounded)
        ->widget(Paragraph::fromText(Text::fromString('This is a block example')))
);
