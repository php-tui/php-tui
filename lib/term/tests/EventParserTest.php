<?php

namespace PhpTui\Term\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\Event;
use PhpTui\Term\EventParser;
use PhpTui\Term\Event\FocusEvent;
use PhpTui\Term\KeyCode;
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
            "\x7F",
            KeyEvent::new(KeyCode::Backspace),
        ];
        /// Enter key.
        yield 'Enter' => [
            "\r",
            KeyEvent::new(KeyCode::Enter),
        ];
        /// Left arrow key.
        yield 'Left' => [
            "\x1B[D",
            KeyEvent::new(KeyCode::Left),
        ];
        /// Right arrow key.
        yield 'Right' => [
            "\x1B[C",
            KeyEvent::new(KeyCode::Right),
        ];
        /// Up arrow key.
        yield 'Up' => [
            "\x1B[A",
            KeyEvent::new(KeyCode::Up),
        ];
        /// Down arrow key.
        yield 'Down' => [
            "\x1B[B",
            KeyEvent::new(KeyCode::Down),
        ];
        /// Home key.
        yield 'Home' => [
            "\x1B[H",
            KeyEvent::new(KeyCode::Home),
        ];
        /// End key.
        yield 'End' => [
            "\x1B[F",
            KeyEvent::new(KeyCode::End),
        ];
        yield 'FocusGained' => [
            "\x1B[I",
            FocusEvent::gained(),
        ];
        yield 'FocusLost' => [
            "\x1B[O",
            FocusEvent::lost(),
        ];
        /// Delete key.
        yield 'Delete' => [
            "\x1B[3~",
            KeyEvent::new(KeyCode::Delete),
        ];
        /// Tab key.
        yield 'Tab' => [
            "\t",
            KeyEvent::new(KeyCode::Tab),
        ];
        /// Page up key.
        yield 'PageUp' => [
            "special key code ",
            KeyEvent::new(KeyCode::PageUp),
        ];
        /// Page down key.
        yield 'PageDown' => [
            "special key code",
            KeyEvent::new(KeyCode::PageDown),
        ];
        /// Shift + Tab key.
        yield 'BackTab' => [
            "special key code",
            KeyEvent::new(KeyCode::BackTab),
        ];
        /// Insert key.
        yield 'Insert' => [
            "special",
            KeyEvent::new(KeyCode::Insert),
        ];
        /// F key.
        ///
        /// `KeyCode::F(1)` represents F1 key; etc.
        yield 'FKey' => [
            "[P",
            KeyEvent::new(KeyCode::FKey),
        ];
        /// A character.
        ///
        /// `KeyCode::Char('c')` represents `c` character; etc.
        yield 'Char' => [
            "a",
            KeyEvent::new(KeyCode::Char),
        ];
    }
}
