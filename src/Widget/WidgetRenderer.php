<?php

declare(strict_types=1);

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Display\Buffer;

interface WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void;
}
