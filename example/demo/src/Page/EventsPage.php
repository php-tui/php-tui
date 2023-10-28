<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ListItem;

final class EventsPage implements Component
{
    /** @var Event[] */
    private array $events = [];

    public function build(): Widget
    {
        return ItemList::default()
            ->block(Block::default()->title(Title::fromString('Event log'))->borders(Borders::ALL))
            ->items(array_map(
                fn (Event $event) => ListItem::new(Text::raw($event->__toString()))->style(Style::default()->fg(AnsiColor::Blue)),
                $this->events
            ));
    }

    public function handle(Event $event): void
    {
        array_unshift($this->events, $event);
    }
}
