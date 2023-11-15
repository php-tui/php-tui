<?php

declare(strict_types=1);

namespace PhpTui\Term\EventProvider;

use PhpTui\Term\Event;
use PhpTui\Term\EventProvider;

final class LoadedEventProvider implements EventProvider
{
    /**
     * @param list<null|Event> $events
     */
    private function __construct(private array $events)
    {
    }

    public static function fromEvents(?Event ...$events): self
    {
        return new self(array_values($events));
    }


    public function next(): ?Event
    {
        return array_shift($this->events);
    }
}
