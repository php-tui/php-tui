<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

interface WidgetSet
{
    /**
     * @return WidgetRenderer[]
     */
    public function renderers(): array;
}
