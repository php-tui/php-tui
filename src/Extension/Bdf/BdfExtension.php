<?php

namespace PhpTui\Tui\Extension\Bdf;

use PhpTui\Tui\Extension\Bdf\Shape\TextRenderer;
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
