<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Model\Widget\Borders;

require 'vendor/autoload.php';

// fullscreen is the default so it can be omitted
$display = DisplayBuilder::default()->fullscreen()->build();
$display->draw(BlockWidget::default()->borders(Borders::ALL));
