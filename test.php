<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Terminal;

require './vendor/autoload.php';

$backend = PhpTermBackend::new();
$terminal = Terminal::fullscreen($backend);
$terminal->enableRawMode();
