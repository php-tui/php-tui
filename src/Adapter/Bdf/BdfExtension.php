<?php

namespace PhpTui\Tui\Adapter\Bdf;

use PhpTui\Tui\Adapter\Bdf\Shape\TextRenderer;
use PhpTui\Tui\Model\DisplayExtension;

class BdfExtension implements DisplayExtension
{
    public function __construct(private ?FontRegistry $registry = null)
    {
    }

    public function shapePainters(): array
    {
        return [
            new TextRenderer($this->fontRegistry()),
        ];
    }

    public function widgetRenderers(): array
    {
        return [];
    }

    private function fontRegistry(): FontRegistry
    {
        return $this->registry ?? FontRegistry::default();
    }
}
