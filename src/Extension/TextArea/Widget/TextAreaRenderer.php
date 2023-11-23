<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextArea\Widget;

use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CompositeWidget;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\ScrollbarWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Position\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Symbol\BlockSet;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;

final class TextAreaRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer): void
    {
        if (!$widget instanceof TextAreaWidget) {
            return;
        }

        $renderer->render(
            $renderer,
            CompositeWidget::fromWidgets(
                BlockWidget::default()->padding(Padding::horizontal(1))
                    ->widget(
                        ScrollbarWidget::default()->state(new ScrollbarState(
                            contentLength: $widget->editor->lineCount(),
                            position: $widget->editor->cursorPosition()->y + 1,
                            viewportContentLength: $buffer->area()->height
                        ))
                    ),
                BlockWidget::default()->padding(Padding::fromScalars(4, 0, 0, 0))
                    ->widget(
                        RawWidget::new(function (Buffer $buffer) use ($widget): void {
                            $area = $buffer->area();
                            $editorCursor = $widget->editor->cursorPosition();
                            $state = $widget->state;
                            $viewportOffset = $this->getViewportOffset($editorCursor, $state, $area);
                            $y = 0;
                            foreach ($widget->editor->viewportLines(
                                $viewportOffset,
                                $area->height
                            ) as $line) {
                                $buffer->putString(Position::at(0, $y), $line, null, $buffer->area()->width);
                                $y++;
                                if ($y > $buffer->area()->height) {
                                    break;
                                }
                            }
                            $state->viewportOffset = $viewportOffset;

                            $this->renderCursor($buffer, $widget);
                        })
                    )
            ),
            $buffer
        );
    }

    private function renderCursor(Buffer $buffer, TextAreaWidget $widget): void
    {
        $cursorCell = $buffer->get($this->cursorPosition($widget));

        if ($cursorCell->char === ' ') {
            $cursorCell->char = BlockSet::FULL;
            $cursorCell->setStyle(Style::default()->white()->onBlack());

            return;
        }

        $cursorCell->setStyle(Style::default()->black()->onWhite());
    }

    private function cursorPosition(TextAreaWidget $widget): Position
    {
        return $widget->editor->cursorPosition()->change(
            fn (int $x, int $y) => [
                $x,
                max(0, $y - $widget->state->viewportOffset)
            ]
        );
    }

    /**
     *    +--------------+
     *    | first line   |
     *    +--------------+ <- viewport offset
     *    | second line  |
     *    |   VIEWPORT   |
     *    | worth line   |
     *    +--------------+ <- viewport offset + viewport height
     *    | █ore text    |
     *    +--------------+
     */
    private function getViewportOffset(Position $editorCursor, TextAreaState $state, Area $area): int
    {
        $viewportOffset = $state->viewportOffset;

        $viewportHeight = $area->height - 1; // cursor is zero based...
        $cursorOffset = $editorCursor->y;

        if ($cursorOffset > $viewportOffset + $viewportHeight) {
            return $cursorOffset - $viewportHeight;
        }

        if ($cursorOffset < $viewportOffset) {
            return $cursorOffset;
        }

        return $viewportOffset;
    }
}
