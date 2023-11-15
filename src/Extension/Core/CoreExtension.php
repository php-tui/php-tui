<?php

namespace PhpTui\Tui\Extension\Core;

use PhpTui\Tui\Model\DisplayExtension;
use PhpTui\Tui\Extension\Core\Shape\CirclePainter;
use PhpTui\Tui\Extension\Core\Shape\ClosurePainter;
use PhpTui\Tui\Extension\Core\Shape\SpritePainter;
use PhpTui\Tui\Extension\Core\Shape\RectanglePainter;
use PhpTui\Tui\Extension\Core\Shape\PointsPainter;
use PhpTui\Tui\Extension\Core\Shape\MapPainter;
use PhpTui\Tui\Extension\Core\Shape\LinePainter;
use PhpTui\Tui\Widget\BlockRenderer;
use PhpTui\Tui\Widget\RawWidgetRenderer;
use PhpTui\Tui\Widget\ItemListRenderer;
use PhpTui\Tui\Widget\GridRenderer;
use PhpTui\Tui\Widget\ChartRenderer;
use PhpTui\Tui\Widget\ParagraphRenderer;
use PhpTui\Tui\Widget\TableRenderer;

class CoreExtension implements DisplayExtension
{
    public function shapePainters(): array
    {
        return [
            new CirclePainter(),
            new LinePainter(),
            new MapPainter(),
            new PointsPainter(),
            new RectanglePainter(),
            new SpritePainter(),
            new ClosurePainter(),
        ];
    }

    public function widgetRenderers(): array
    {
        return [
            new BlockRenderer(),
            new ParagraphRenderer(),
            new ChartRenderer(),
            new GridRenderer(),
            new ItemListRenderer(),
            new RawWidgetRenderer(),
            new TableRenderer(),
        ];
    }
}
