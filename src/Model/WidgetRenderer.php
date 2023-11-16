<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

interface WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void;
}
