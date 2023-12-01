<?php

declare(strict_types=1);

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

interface WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void;
}
