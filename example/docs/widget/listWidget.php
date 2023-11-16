<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\List\ListState;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Model\Widget\Text;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    ListWidget::default()
        ->highlightSymbol('😼')
        ->state(new ListState(0, 2))
        ->items(
            ListItem::new(Text::fromString('Item one')),
            ListItem::new(Text::fromString('Item two')),
            ListItem::new(Text::fromString('Item three')),
            ListItem::new(Text::fromString('Item four')),
        )
);
