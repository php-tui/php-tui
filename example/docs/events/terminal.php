<?php

declare(strict_types=1);

use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;

require 'vendor/autoload.php';

$terminal = Terminal::new();
$display = DisplayBuilder::default(PhpTermBackend::new($terminal))->build();
