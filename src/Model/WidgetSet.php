<?php

namespace PhpTui\Tui\Model;

interface WidgetSet
{
    /**
     * @return WidgetRenderer[]
     */
    public function renderers(): array;
}
