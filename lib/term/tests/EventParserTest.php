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
    public function testParse(string $line, ?Event $expected, ?bool $moreInput = false): void
    {
        $parser = new EventParser();
        $parser->advance($line, $moreInput);
        $events = $parser->drain();
        if (null === $expected) {
            self::assertCount(0, $events);
            return;
        }
        self::assertCount(1, $events);
        self::assertEquals($expected, $events[0]);
    }

    /**
     * @return Generator<array{0:string,1:?KeyEvent,2?:bool}>
     */
    public static function provideParse(): Generator
    {
        yield 'esc' => [
            "\x1B",
            KeyEvent::new(KeyCode::Esc),
        ];
        yield 'possible esc sequence' => [
            "\x1B",
            null,
            true,
        ];
        yield 'Backspace' => [
            '',
            KeyEvent::new(KeyCode::Backspace),
        ];
        /// Enter key.
        yield 'Enter' => [
            '',
            KeyEvent::new(KeyCode::Enter),
        ];
        /// Left arrow key.
        yield 'Left' => [
            '',
            KeyEvent::new(KeyCode::Left),
        ];
        /// Right arrow key.
        yield 'Right' => [
            '',
            KeyEvent::new(KeyCode::Right),
        ];
        /// Up arrow key.
        yield 'Up' => [
            '',
            KeyEvent::new(KeyCode::Up),
        ];
        /// Down arrow key.
        yield 'Down' => [
            '',
            KeyEvent::new(KeyCode::Down),
        ];
        /// Home key.
        yield 'Home' => [
            '',
            KeyEvent::new(KeyCode::Home),
        ];
        /// End key.
        yield 'End' => [
            '',
            KeyEvent::new(KeyCode::End),
        ];
        /// Page up key.
        yield 'PageUp' => [
            '',
            KeyEvent::new(KeyCode::PageUp),
        ];
        /// Page down key.
        yield 'PageDown' => [
            '',
            KeyEvent::new(KeyCode::PageDown),
        ];
        /// Tab key.
        yield 'Tab' => [
            '',
            KeyEvent::new(KeyCode::Tab),
        ];
        /// Shift + Tab key.
        yield 'BackTab' => [
            '',
            KeyEvent::new(KeyCode::BackTab),
        ];
        /// Delete key.
        yield 'Delete' => [
            '',
            KeyEvent::new(KeyCode::Delete),
        ];
        /// Insert key.
        yield 'Insert' => [
            '',
            KeyEvent::new(KeyCode::Insert),
        ];
        /// F key.
        ///
        /// `KeyCode::F(1)` represents F1 key; etc.
        yield 'FKey' => [
            '',
            KeyEvent::new(KeyCode::FKey),
        ];
        /// A character.
        ///
        /// `KeyCode::Char('c')` represents `c` character; etc.
        yield 'Char' => [
            '',
            KeyEvent::new(KeyCode::Char),
        ];
        /// Null.
        yield 'Null' => [
            '',
            KeyEvent::new(KeyCode::Null),
        ];
    }
}
