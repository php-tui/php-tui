<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CompositeWidget;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Widget\Borders;

final class CompositeRendererTest extends WidgetTestCase
{
    public function testComposite(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        $this->render($buffer, CompositeWidget::fromWidgets(
            BlockWidget::default()->borders(Borders::ALL),
            ScrollbarWidget::default()->state(new ScrollbarState(20, 5, 5)),
        ));

        self::assertEquals([
            '▲───┐',
            '█   │',
            '║   │',
            '║   │',
            '▼───┘',
        ], $buffer->toLines());
    }

    public function testNoWidgets(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        $this->render($buffer, CompositeWidget::fromWidgets(
        ));

        self::assertEquals([
            '     ',
            '     ',
            '     ',
            '     ',
            '     ',
        ], $buffer->toLines());
    }
}
