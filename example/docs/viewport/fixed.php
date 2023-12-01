<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Model\Borders;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->fixed(2, 2, 4, 4)->build();
$display->draw(BlockWidget::default()->borders(Borders::ALL));
