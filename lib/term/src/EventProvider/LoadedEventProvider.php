<?php

namespace PhpTui\Term\EventProvider;

use PhpTui\Term\Event;
use PhpTui\Term\EventProvider;

final class LoadedEventProvider implements EventProvider
{
    /**
     * @param Event[] $events
     */
    private function __construct(private array $events)
    {
    }

    public static function fromEvents(Event ...$events): self
    {
        return new self($events);
    }


    public function next(): ?Event
    {
        return array_shift($this->events);
    }
}
