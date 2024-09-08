<?php

declare(strict_types=1);

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

require 'vendor/autoload.php';

final class MyCustomWidget implements Widget
{
}

final class MyCustomRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        if (!$widget instanceof MyCustomWidget) {
            return;
        }

        $buffer->putString(Position::at(0, 0), 'Hello World!');
    }
}

$display = DisplayBuilder::default()
    ->addWidgetRenderer(new MyCustomRenderer())
    ->build();
$display->draw(new MyCustomWidget());
