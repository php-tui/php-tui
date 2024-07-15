<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\MouseEvent;
use PhpTui\Term\MouseEventKind;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;
use PhpTui\Tui\Widget\HorizontalAlignment;
use PhpTui\Tui\Widget\Widget;

final class WindowPage implements Component
{
    private ?MouseEvent $event = null;

    private Position $position;

    private bool $dragging = false;

    public function __construct()
    {
        $this->position = Position::at(10, 10);
    }

    public function build(): Widget
    {
        return BufferWidget::new(function (BufferContext $context): void {
            $area = Area::fromScalars(
                $this->position->x,
                $this->position->y,
                20,
                10
            );

            $title = Title::fromLine(Line::fromString('Drag me'));
            if (
                $this->event && $this->event->kind === MouseEventKind::Drag
            ) {
                $position = Position::at($this->event->column, $this->event->row);
                if ($this->dragging === true || $area->containsPosition($position)) {
                    $this->dragging = true;
                    $this->position = $position;
                    $title = Title::fromLine(Line::fromString('Dragging')->red()->onGreen());
                }
            } else {
                $this->dragging = false;
            }

            $area = Area::fromScalars(
                max(
                    $context->area->left(),
                    min($this->position->x, max(0, $context->area->right() - 20))
                ),
                min(
                    max(
                        $context->area->top(),
                        $this->position->y
                    ),
                    max(
                        0,
                        $context->area->bottom() - 10
                    )
                ),
                20,
                10
            );

            $block = BlockWidget::default()
                ->borders(Borders::ALL)
                ->titles(
                    $title,
                    Title::fromString($this->position->__toString())->horizontalAlignmnet(HorizontalAlignment::Right)
                );
            $context->draw($block, $area);
        });
    }

    public function handle(Event $event): void
    {
        if ($event instanceof MouseEvent) {
            $this->event = $event;
        }
    }
}
