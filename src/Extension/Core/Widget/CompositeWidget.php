<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Widget\Widget;

/**
 * Render multiple widgets to the same area.
 *
 * In a grid layout each widget will render to an empty buffer.
 *
 * This widget enables each widget to overlay widgets on the _same_ buffer
 * which is useful for showing dialogues, overlaying scrollbars, floating
 * windows, etc.
 */
final class CompositeWidget implements Widget
{
    public function __construct(
        /**
         * @var Widget[] $widgets
         */
        public readonly array $widgets
    ) {
    }

    public static function fromWidgets(
        Widget ...$widgets
    ): self {
        return new self($widgets);
    }
}
