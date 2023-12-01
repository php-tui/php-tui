<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Widget\Widget;

/**
 * This widget allows you to write directly to the buffer through a closure.
 */
final class BufferWidget implements Widget
{
    public function __construct(
        /**
         * The callback for writing to the buffer.
         * @var Closure(BufferContext $buffer):void
         */
        public Closure $widget
    ) {
    }

    /**
     * @param Closure(BufferContext $buffer):void $closure
     */
    public static function new(Closure $closure): self
    {
        return new self($closure);
    }
}
