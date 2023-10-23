<?php

namespace PhpTui\Term;

use Generator;

final class EventProvider
{
    public function __construct(private Reader $reader, private EventParser $parser)
    {
    }

    /**
     * @return Generator<Event>
     */
    public function read(): Generator
    {
        while (null !== $line = $this->reader->read()) {
            try {
                $event = $this->parser->advance($line);
                if ($result === null) {
                    continue;
                }
                yield $event;
            } catch (ParserError $e) {
                return $e;
            }
        }
    }
}
