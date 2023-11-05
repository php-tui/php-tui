<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\WidgetSet;

class DefaultWidgetSet implements WidgetSet
{
    public function renderers(): array
    {
        return [
            new BlockRenderer(),
            new ParagraphRenderer(),
            new CanvasRenderer(),
            new ChartRenderer(),
            new GridRenderer(),
            new ItemListRenderer(),
            new RawWidgetRenderer(),
            new TableRenderer(),
        ];
    }
}
