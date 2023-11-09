<?php

namespace PhpTui\Tui\Adapter\Bdf;

use PhpTui\Tui\Adapter\Bdf\Shape\TextRenderer;
use PhpTui\Tui\Model\Canvas\ShapeSet;

class BdfShapeSet implements ShapeSet
{
    public function __construct(
        private FontRegistry $registry
    ) {
    }
    public function shapes(): array
    {
        return [
            new TextRenderer($this->registry)
        ];
    }
}
