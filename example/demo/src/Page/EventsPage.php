<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;

final class EventsPage implements Component
{
    /** @var Event[] */
    private array $events = [];

    public function build(): Widget
    {
        return GridWidget::default()
            ->constraints(
                Constraint::min(3),
                Constraint::min(3),
            )
            ->widgets(
                BlockWidget::default()
                    ->padding(Padding::left(1))
                    ->widget(
                        ParagraphWidget::fromLines(
                            Line::parse('Welcome to the <fg=white;options=bold>PHP-TUI 🐘</> demo application.'),
                            Line::parse('Use the <fg=#ffa500>tab</> to go to the next page and <fg=#ffa500>shift-tab</> to go to the previous page.'),
                            Line::parse('<fg=white>Below you can see a log of all the input events, try moving the mouse!</> 🐭'),
                        ),
                    ),
                BlockWidget::default()->titles(Title::fromString('Event log'))->borders(Borders::ALL)
                   ->widget(
                       ListWidget::default()
                            ->items(...array_map(fn (Event $event) => ListItem::new(Text::fromString($event->__toString())), $this->events))
                   )
            )
        ;
    }

    public function handle(Event $event): void
    {
        array_unshift($this->events, $event);
    }
}
