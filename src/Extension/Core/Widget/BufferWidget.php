<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Widget\Widget;

/**
 * This widget provides access to the Buffer and allows you
 * to mutate it directly in addition to being able to draw widgets.
 *
 * This is useful if you need to know the context upon which widgets are being
 * drawn (for example the absolute position of the containing area etc).
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
