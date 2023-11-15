<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\ItemList;
use PhpTui\Tui\Extension\Core\Widget\ItemList\ListItem;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;

final class EventsPage implements Component
{
    /** @var Event[] */
    private array $events = [];

    public function build(): Widget
    {
        return Block::default()->titles(Title::fromString('Event log'))->borders(Borders::ALL)
            ->widget(
                ItemList::default()
                    ->items(...array_map(fn (Event $event) => ListItem::new(Text::fromString($event->__toString())), $this->events))
            )
        ;
    }

    public function handle(Event $event): void
    {
        array_unshift($this->events, $event);
    }
}
