<?php

namespace PhpTui\Tui\Tests\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PhpTui\Tui\Extension\Core\Shape\DefaultShapeSet;
use PhpTui\Tui\Extension\Core\Widget\DefaultWidgetSet;

class WidgetTestCase extends TestCase
{

    protected function render(Buffer $buffer, Widget $widget): void
    {
        $this->renderer()->render(
            new NullWidgetRenderer(),
            $widget,
            $buffer->area(),
            $buffer
        );
    }

    /**
     * @return string[]
     */
    protected function renderToLines(Widget $widget, int $width = 8, int $height = 5): array
    {
        $area = Area::fromDimensions($width, $height);
        $buffer = Buffer::empty($area);
        $this->renderer()->render(new NullWidgetRenderer(), $widget, $area, $buffer);
        return $buffer->toLines();
    }
    private function renderer(): WidgetRenderer
    {
        return AggregateWidgetRenderer::fromWidgetSets(
            new DefaultWidgetSet(AggregateShapePainter::fromShapeSets(new DefaultShapeSet()))
        );
    }
}
