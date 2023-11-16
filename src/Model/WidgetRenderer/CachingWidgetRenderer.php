<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\WidgetRenderer;

use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class CachingWidgetRenderer implements WidgetRenderer
{
    private WidgetRenderer $innerRenderer;

    /**
     * @var array<string,Buffer>
     */
    private array $cache = [];

    public function __construct(WidgetRenderer $inner)
    {
        $this->innerRenderer = $inner;
    }

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        $cacheKey = 'cache';
        if (isset($this->cache[$cacheKey])) {
            $buffer->putBuffer(Position::at(0, 0), $this->cache[$cacheKey]);

            return;
        }
        $this->innerRenderer->render($renderer, $widget, $buffer);
        $this->cache[$cacheKey] = $buffer;
    }
}
