<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\WidgetSet;

class DefaultWidgetSet implements WidgetSet
{
    public function __construct(private ShapePainter $shapePainter)
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
            new ItemListRenderer(),
            new RawWidgetRenderer(),
            new TableRenderer(),
            new PhpCodeRenderer(),
        ];
    }
}
