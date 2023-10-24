<?php

namespace PhpTui\Term\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\Event;
use PhpTui\Term\EventParser;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\FocusEvent;
use PhpTui\Term\Event\FunctionKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyModifiers;

class EventParserTest extends TestCase
{
    /**
     * @dataProvider provideParse
     * @dataProvider provideCsiSpecialKeyCode
     * dataProvider provideCsiUEncoded
     */
    public function testParse(string $line, ?Event $expected, bool $moreInput = false): void
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
     * @return Generator<array{0:string,1:?Event,2?:bool}>
     */
    public static function provideParse(): Generator
    {
        yield 'esc' => [
            "\x1B",
            CodedKeyEvent::new(KeyCode::Esc),
        ];
        yield 'possible esc sequence' => [
            "\x1B",
            null,
            true,
        ];
        yield 'Backspace' => [
            "\x7F",
            CodedKeyEvent::new(KeyCode::Backspace),
        ];
        /// Enter key.
        yield 'Enter' => [
            "\r",
            CodedKeyEvent::new(KeyCode::Enter),
        ];
        /// Left arrow key.
        yield 'Left' => [
            "\x1B[D",
            CodedKeyEvent::new(KeyCode::Left),
        ];
        /// Right arrow key.
        yield 'Right' => [
            "\x1B[C",
            CodedKeyEvent::new(KeyCode::Right),
        ];
        /// Up arrow key.
        yield 'Up' => [
            "\x1B[A",
            CodedKeyEvent::new(KeyCode::Up),
        ];
        /// Down arrow key.
        yield 'Down' => [
            "\x1B[B",
            CodedKeyEvent::new(KeyCode::Down),
        ];
        /// Home key.
        yield 'Home' => [
            "\x1B[H",
            CodedKeyEvent::new(KeyCode::Home),
        ];
        /// End key.
        yield 'End' => [
            "\x1B[F",
            CodedKeyEvent::new(KeyCode::End),
        ];
        yield 'FocusGained' => [
            "\x1B[I",
            FocusEvent::gained(),
        ];
        yield 'FocusLost' => [
            "\x1B[O",
            FocusEvent::lost(),
        ];
        /// Tab key.
        yield 'Tab' => [
            "\t",
            CodedKeyEvent::new(KeyCode::Tab),
        ];
        /// F key.
        ///
        /// `KeyCode::F(1)` represents F1 key; etc.
        yield 'F1' => [
            "\x1B[P",
            FunctionKeyEvent::new(1),
        ];
        yield 'F2' => [
            "\x1B[Q",
            FunctionKeyEvent::new(2),
        ];
        yield 'F3' => [
            "\x1B[R",
            FunctionKeyEvent::new(3),
        ];
        yield 'F4' => [
            "\x1B[S",
            FunctionKeyEvent::new(4),
        ];
        /// A character.
        ///
        /// `KeyCode::Char('c')` represents `c` character; etc.
        yield 'Char' => [
            "a",
            CharKeyEvent::new('a'),
        ];
        yield 'Uppercase Char' => [
            "A",
            CharKeyEvent::new('A', KeyModifiers::SHIFT),
        ];
    }

    /**
     * @return Generator<array{0:string,1:?Event,2?:bool}>
     */
    public static function provideCsiSpecialKeyCode(): Generator
    {
        /// Delete key.
        yield 'Delete' => [
            "\x1B[3~",
            CodedKeyEvent::new(KeyCode::Delete),
        ];
        /// Home key.
        yield 'Home 1' => [
            "\x1B[1~",
            CodedKeyEvent::new(KeyCode::Home),
        ];
        /// Home key.
        yield 'Home 2' => [
            "\x1B[7~",
            CodedKeyEvent::new(KeyCode::Home),
        ];
        /// Insert key.
        yield 'Insert' => [
            "\x1B[2~",
            CodedKeyEvent::new(KeyCode::Insert),
        ];
        yield 'CSI End 1' => [
            "\x1B[4~",
            CodedKeyEvent::new(KeyCode::End),
        ];
        yield 'CSI End 2' => [
            "\x1B[8~",
            CodedKeyEvent::new(KeyCode::End),
        ];
        /// Page up key.
        yield 'PageUp' => [
            "\x1B[5~",
            CodedKeyEvent::new(KeyCode::PageUp),
        ];
        /// Page down key.
        yield 'PageDown' => [
            "\x1B[6~",
            CodedKeyEvent::new(KeyCode::PageDown),
        ];
    }

    public function provideCsiUEncoded(): Generator
    {
        /// Shift + Tab key.
        yield 'BackTab' => [
            "special key code",
            CodedKeyEvent::new(KeyCode::BackTab),
        ];
    }
}