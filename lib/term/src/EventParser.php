<?php

namespace PhpTui\Term;

use Event;

class EventParser
{
    private array $buffer = [];
    private array $events = [];

    /**
     * @return Event[]
     */
    public function drain(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    public function advance(string $line): void
    {
        // split string into bytes
        $bytes = str_split($line);

        foreach ($bytes as $byte) {
            try {
                $event = $this->parseEvent();
                if ($event === null) {
                    continue;
                }
            } catch (ParseError $error) {
                continue;
            }
        }
    }

    private function parseEvent(): ?Event
    {
    }
}
