<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use PhpTui\Tui\Model\Canvas\ShapePainter;

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
