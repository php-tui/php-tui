<?php

namespace PhpTui\Tui\Model;

use PhpTui\Tui\Model\Canvas\ShapePainter;

interface WidgetSet
{
    /**
     * @return WidgetRenderer[]
     */
    public function renderers(): array;

    /**
     * @return ShapePainter[]
     */
    public function painters(): array;
}
