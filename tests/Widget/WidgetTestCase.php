<?php

namespace PhpTui\Tui\Tests\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PhpTui\Tui\Widget\BlockRenderer;
use PhpTui\Tui\Widget\CanvasRenderer;
use PhpTui\Tui\Widget\ChartRenderer;
use PhpTui\Tui\Widget\DefaultWidgetSet;
use PhpTui\Tui\Widget\GridRenderer;
use PhpTui\Tui\Widget\ItemListRenderer;
use PhpTui\Tui\Widget\ParagraphRenderer;
use PhpTui\Tui\Widget\RawWidget;
use PhpTui\Tui\Widget\RawWidgetRenderer;
use PhpTui\Tui\Widget\TableRenderer;

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
            new DefaultWidgetSet()
        );
    }
}
