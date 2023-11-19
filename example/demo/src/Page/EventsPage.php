<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
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
        return BlockWidget::default()->titles(Title::fromString('Event log'))->borders(Borders::ALL)
            ->widget(
                ListWidget::default()
                    ->items(...array_map(fn (Event $event) => ListItem::new(Text::fromString($event->__toString())), $this->events))
            )
        ;
    }

    public function handle(Event $event): void
    {
        array_unshift($this->events, $event);
    }
}
