<?php

namespace PhpTui\Term\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\Event;
use PhpTui\Term\EventParser;
use PhpTui\Term\Event\KeyCode;
use PhpTui\Term\Event\KeyEvent;

class EventParserTest extends TestCase
{
    /**
     * @dataProvider provideParse
     */
    public function testParse(string $line, Event $event): void
    {
        $parser = new EventParser();
        $parser->advance($line);
        $events = $parser->drain();
        self::assertCount(1, $events);
        self::assertEquals($events, $events[0]);
    }

    /**
     * @return Generator<array{string,KeyEvent}>
     */
    public static function provideParse(): Generator
    {
        yield [
            "\x1Bc",
            KeyEvent::new(KeyCode::Esc)
        ];
    }
}
