<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use PhpTui\Tui\Canvas\ShapePainter;
use PhpTui\Tui\Widget\WidgetRenderer;

interface DisplayExtension
{
    /**
     * @return ShapePainter[]
     */
    public function shapePainters(): array;

    /**
     * @return WidgetRenderer[]
     */
    public function widgetRenderers(): array;
}
