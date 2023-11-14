<?php

namespace PhpTui\Tui\Adapter\Bdf;

use PhpTui\Tui\Adapter\Bdf\Shape\TextRenderer;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\DisplayExtension;

class BdfExtension implements DisplayExtension
{
    public function __construct(private ?FontRegistry $registry = null)
    {
    }

    public function build(DisplayBuilder $builder): void
    {
        $builder->addShapePainter(new TextRenderer(
            $this->fontRegistry(),
        ));
    }

    private function fontRegistry(): FontRegistry
    {
        return $this->fontRegistry ?? FontRegistry::default();
    }
}
