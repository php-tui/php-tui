<?php

namespace PhpTui\Tui\Tests\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PhpTui\Tui\Widget\BlockRenderer;
use PhpTui\Tui\Widget\CanvasRenderer;
use PhpTui\Tui\Widget\ParagraphRenderer;

class WidgetTestCase extends TestCase
{
    protected function render(Buffer $buffer, Widget $widget): void
    {
        (new AggregateWidgetRenderer([
            new BlockRenderer(),
            new ParagraphRenderer(),
            new CanvasRenderer(),
        ]))->render(
            new NullWidgetRenderer(),
            $widget,
            $buffer->area(),
            $buffer
        );
    }
}
