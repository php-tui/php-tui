<?php

namespace PhpTui\Tui\Example\Demo;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;

interface Component
{
    public function build(): Widget;

    public function handle(Event $event): void;
}
