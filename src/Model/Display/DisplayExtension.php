<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Display;

use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\WidgetRenderer;

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
