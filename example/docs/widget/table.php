
<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Table;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Model\Constraint;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    Table::default()
        ->widths(
            Constraint::percentage(25),
            Constraint::percentage(25),
            Constraint::percentage(50),
        )
        ->header(
            TableRow::fromCells([
                TableCell::fromString('Name'),
                TableCell::fromString('Quantity'),
                TableCell::fromString('Approved?'),
            ])->height(2)->bottomMargin(2)
        )
        ->rows(
            TableRow::fromCells([
                TableCell::fromString('Cliff'),
                TableCell::fromString('1'),
                TableCell::fromString('✅'),
            ]),
            TableRow::fromCells([
                TableCell::fromString('Tree'),
                TableCell::fromString('15'),
                TableCell::fromString('✅'),
            ]),
            TableRow::fromCells([
                TableCell::fromString('Slate'),
                TableCell::fromString('519'),
                TableCell::fromString('✅'),
            ]),
        )
);
