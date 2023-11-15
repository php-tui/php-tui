<?php

namespace PhpTui\Tui\Extension\Core\Shape;

use Closure;
use PhpTui\Tui\Model\Canvas\Painter;
use PhpTui\Tui\Model\Canvas\Shape;

class ClosureShape implements Shape
{
    /**
     * @param Closure(Painter):void $closure
     */
    public function __construct(public readonly Closure $closure)
    {
    }
}
