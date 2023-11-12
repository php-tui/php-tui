<?php

namespace PhpTui\Tui\Tests;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Canvas\ShapeSet;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetSet;
use PhpTui\Tui\Widget\ClosureRenderer;
use PhpTui\Tui\Widget\Paragraph;

final class DisplayBuilderTest extends TestCase
{
    public function testBuildDefault(): void
    {
        $display = DisplayBuilder::new()->build();
        $this->addToAssertionCount(1);
    }

    public function testAddWidgetAndShapeSets(): void
    {
        $captured = false;
        $shapeSet = $this->getMockBuilder(ShapeSet::class)->getMock();
        $widgetSet = $this->getMockBuilder(WidgetSet::class)->getMock();
        $widgetSet->method('renderers')->willReturn([new ClosureRenderer(
            function (WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer) use (&$captured): void {
                $captured = true;
            }
        )]);

        $dummy = new DummyBackend(10, 10);
        $display = DisplayBuilder::new($dummy)
            ->addShapeSet($shapeSet)
            ->addWidgetSet($widgetSet)
            ->fullscreen()
            ->build();
        $display->drawWidget(Paragraph::fromString('hello'));

        self::assertTrue($captured);
    }
}
