<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Widget\CanvasRenderer;
use PhpTui\Tui\Model\WidgetSet;

class DefaultWidgetSet implements WidgetSet
{
    public function __construct(private readonly ShapePainter $shapePainter)
    {
    }

    public function renderers(): array
    {
        return [
            new BlockRenderer(),
            new ParagraphRenderer(),
            new CanvasRenderer($this->shapePainter),
            new ChartRenderer(),
            new GridRenderer(),
            new ListRenderer(),
            new RawWidgetRenderer(),
            new TableRenderer(),
            new GaugeRenderer(),
            new BarChartRenderer(),
        ];
    }
}
