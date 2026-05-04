<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Shape;

use Closure;
use PhpTui\Tui\Canvas\Painter;
use PhpTui\Tui\Canvas\Shape;

/**
 * Shape that can write directly to the Painter context
 */
final readonly class ClosureShape implements Shape
{
    /**
     * @param Closure(Painter):void $closure
     */
    public function __construct(public Closure $closure)
    {
    }
}
