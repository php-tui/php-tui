<?php

namespace PhpTui\Tui\Model;

interface WidgetRenderer
{
    public function render(Widget $widget, Area $area, Buffer $buffer): void;
}
