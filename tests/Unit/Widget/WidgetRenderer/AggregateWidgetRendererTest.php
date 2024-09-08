<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Widget\WidgetRenderer;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;
use PhpTui\Tui\Widget\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Widget\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

final class AggregateWidgetRendererTest extends TestCase
{
    public function testSelfRenderingWidget(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(10, 10));
        (new AggregateWidgetRenderer([]))->render(
            new NullWidgetRenderer(),
            new ExampleWidget(),
            $buffer,
            Area::fromDimensions(10, 10)
        );
        self::assertStringContainsString('Hello', $buffer->toString());
    }
}

final class ExampleWidget implements WidgetRenderer, Widget
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
    }
}
