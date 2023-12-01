
<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\TableWidget;
use PhpTui\Tui\Layout\Constraint;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    TableWidget::default()
        ->widths(
            Constraint::percentage(25),
            Constraint::percentage(25),
            Constraint::percentage(50),
        )
        ->header(
            TableRow::fromStrings(
                'Name',
                'Quantity',
                'Approved?',
            )->height(2)->bottomMargin(2)
        )
        ->rows(
            TableRow::fromCells(
                TableCell::fromString('Cliff'),
                TableCell::fromString('1'),
                TableCell::fromString('✅'),
            ),
            TableRow::fromStrings(
                'Tree',
                '15',
                '✅',
            ),
            TableRow::fromStrings(
                'Slate',
                '519',
                '✅',
            ),
        )
);
