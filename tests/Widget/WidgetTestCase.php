<?php

namespace PhpTui\Tui\Tests\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Widget\BlockRenderer;

class WidgetTestCase extends TestCase
{
    protected function render(Buffer $buffer, Widget $widget): void
    {
        (new AggregateWidgetRenderer([
            new BlockRenderer(),
        ]))->render($widget, $buffer->area(), $buffer);
    }
}
