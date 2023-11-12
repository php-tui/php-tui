<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ItemListState;
use PhpTui\Tui\Widget\ItemList\ListItem;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    ItemList::default()
        ->highlightSymbol('😼')
        ->state(new ItemListState(0, 2))
        ->items(
            ListItem::new(Text::fromString('Item one')),
            ListItem::new(Text::fromString('Item two')),
            ListItem::new(Text::fromString('Item three')),
            ListItem::new(Text::fromString('Item four')),
        )
);
