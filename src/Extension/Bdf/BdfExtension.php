<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Bdf;

use PhpTui\Tui\Display\DisplayExtension;
use PhpTui\Tui\Extension\Bdf\Shape\TextRenderer;

final class BdfExtension implements DisplayExtension
{
    public function __construct(private readonly ?FontRegistry $registry = null)
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
