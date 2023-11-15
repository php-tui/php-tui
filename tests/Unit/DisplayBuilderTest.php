<?php

namespace PhpTui\Tui\Tests;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\DisplayExtension;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Extension\Core\Shape\ClosurePainter;
use PhpTui\Tui\Extension\Core\Shape\ClosureShape;
use PhpTui\Tui\Extension\Core\Widget\Canvas;
use PhpTui\Tui\Extension\Core\Widget\ClosureRenderer;

final class Unit extends TestCase
{
    public function testBuildDefault(): void
    {
        $dummy = new DummyBackend(10, 10);
        $display = DisplayBuilder::default($dummy)->build();
        $this->addToAssertionCount(1);
    }

    public function testAddExtension(): void
    {
        $widgetRendered = false;
        $shapePainted = false;
        $extension = $this->getMockBuilder(DisplayExtension::class)->getMock();
        $extension->method('widgetRenderers')->willReturn([new ClosureRenderer(
            function (WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer) use (&$widgetRendered): void {
                $widgetRendered = true;
            }
        )]);
        $extension->method('shapePainters')->willReturn([new ClosurePainter()]);

        $dummy = new DummyBackend(10, 10);
        $display = DisplayBuilder::default($dummy)
            ->addExtension($extension)
            ->fullscreen()
            ->build();
        $display->draw(
            Canvas::default()->draw(new ClosureShape(function () use (&$shapePainted): void {
                $shapePainted = true;
            }))
        );

        self::assertTrue($widgetRendered, 'widget rendered');
        self::assertTrue($shapePainted, 'shape painted');
    }
}
