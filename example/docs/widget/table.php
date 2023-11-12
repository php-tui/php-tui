
<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Widget\Table;
use PhpTui\Tui\Widget\Table\TableCell;
use PhpTui\Tui\Widget\Table\TableRow;

require 'vendor/autoload.php';

$display = DisplayBuilder::new(PhpTermBackend::new())->build();
$display->drawWidget(
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
