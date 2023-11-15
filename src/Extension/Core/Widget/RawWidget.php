<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget;

/**
 * This widget allows you to write directly to the buffer through a closure.
 */
final class RawWidget implements Widget
{
    public function __construct(
        /**
         * The callback for writing to the buffer.
         * @var Closure(Buffer $buffer):void
         */
        public Closure $widget
    ) {
    }

    /**
     * @param Closure(Buffer $buffer):void $closure
     */
    public static function new(Closure $closure): self
    {
        return new self($closure);
    }
}
