<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use PhpTui\Tui\Canvas\AggregateShapePainter;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\CoreExtension;
use PhpTui\Tui\Extension\Core\Widget\CanvasRenderer;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;
use PhpTui\Tui\Widget\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Widget\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

abstract class WidgetTestCase extends TestCase
{
    protected function render(Buffer $buffer, Widget $widget): void
    {
        $this->renderer()->render(
            new NullWidgetRenderer(),
            $widget,
            $buffer,
            $buffer->area(),
        );
    }

    /**
     * @return string[]
     * @param int<0,max> $width
     * @param int<0,max> $height
     */
    protected function renderToLines(Widget $widget, int $width = 8, int $height = 5): array
    {
        $area = Area::fromDimensions($width, $height);
        $buffer = Buffer::empty($area);
        $this->renderer()->render(new NullWidgetRenderer(), $widget, $buffer, $buffer->area());

        return $buffer->toLines();
    }
    private function renderer(): WidgetRenderer
    {
        $coreExtension = new CoreExtension();

        return new AggregateWidgetRenderer([
            new CanvasRenderer(
                new AggregateShapePainter($coreExtension->shapePainters())
            ),
            ...$coreExtension->widgetRenderers()
        ]);
    }
}
