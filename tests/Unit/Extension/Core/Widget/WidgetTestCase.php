<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Shape\DefaultShapeSet;
use PhpTui\Tui\Extension\Core\Widget\DefaultWidgetSet;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PHPUnit\Framework\TestCase;

class WidgetTestCase extends TestCase
{
    protected function render(Buffer $buffer, Widget $widget): void
    {
        $this->renderer()->render(
            new NullWidgetRenderer(),
            $widget,
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
        $this->renderer()->render(new NullWidgetRenderer(), $widget, $buffer);

        return $buffer->toLines();
    }
    private function renderer(): WidgetRenderer
    {
        return AggregateWidgetRenderer::fromWidgetSets(
            new DefaultWidgetSet(AggregateShapePainter::fromShapeSets(new DefaultShapeSet()))
        );
    }
}
