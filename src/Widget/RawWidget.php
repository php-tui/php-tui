<?php

namespace PhpTui\Tui\Widget;

use Closure;
use PhpTui\Tui\Model\Area;
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
        private Closure $widget
    ) {}

    /**
     * @param Closure(Buffer $buffer):void $closure
     */
    public static function new(Closure $closure): self
    {
        return new self($closure);
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $subBuffer = Buffer::empty($area);
        ($this->widget)($subBuffer);
        $buffer->putBuffer($area->position, $subBuffer);
    }
}
