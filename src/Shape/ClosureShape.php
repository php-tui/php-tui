<?php

namespace PhpTui\Tui\Shape;

use Closure;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;

class ClosureShape implements Shape
{
    /**
     * @param Closure(Painter):void $closure
     */
    public function __construct(private Closure $closure)
    {
    }

    public function draw(Painter $painter): void
    {
        ($this->closure)($painter);
    }
}
