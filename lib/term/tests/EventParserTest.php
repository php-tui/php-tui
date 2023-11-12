<?php

namespace PhpTui\Term\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\Event;
use PhpTui\Term\EventParser;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\FocusEvent;
use PhpTui\Term\Event\FunctionKeyEvent;
use PhpTui\Term\Event\MouseEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyEventKind;
use PhpTui\Term\KeyModifiers;
use PhpTui\Term\MouseButton;
use PhpTui\Term\MouseEventKind;

class EventParserTest extends TestCase
{
    /**
     * @dataProvider provideParse
     * @dataProvider provideCsiSpecialKeyCode
     * @dataProvider provideCsiModifierKeyCode
     * @dataProvider provideCsiMouse
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
        yield 'BackTab' => [
            "\x1B[Z",
            CodedKeyEvent::new(KeyCode::BackTab, KeyModifiers::SHIFT),
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
        yield 'Left (D)' => [
            "\x1BOD",
            CodedKeyEvent::new(KeyCode::Left),
        ];
        yield 'Right (C)' => [
            "\x1BOC",
            CodedKeyEvent::new(KeyCode::Right),
        ];
        yield 'Up (A)' => [
            "\x1BOA",
            CodedKeyEvent::new(KeyCode::Up),
        ];
        yield 'Down (B)' => [
            "\x1BOB",
            CodedKeyEvent::new(KeyCode::Down),
        ];
        yield 'Home (H)' => [
            "\x1BOH",
            CodedKeyEvent::new(KeyCode::Home),
        ];
        yield 'End (F)' => [
            "\x1BOF",
            CodedKeyEvent::new(KeyCode::End),
        ];
        yield 'F1 (P)' => [
            "\x1BOP",
            FunctionKeyEvent::new(1),
        ];
        yield 'F2 (Q)' => [
            "\x1BOQ",
            FunctionKeyEvent::new(2),
        ];
        yield 'F3 (R)' => [
            "\x1BOR",
            FunctionKeyEvent::new(3),
        ];
        yield 'F4 (S)' => [
            "\x1BOS",
            FunctionKeyEvent::new(4),
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
        yield 'double escape' => [
            "\x1B\x1B",
            CodedKeyEvent::new(KeyCode::Esc),
        ];
        yield 'escape then chra' => [
            "\x1Ba",
            CharKeyEvent::new('a'),
        ];
        /// A character.
        ///
        /// `KeyCode::Char('c')` represents `c` character; etc.
        yield 'Char' => [
            'a',
            CharKeyEvent::new('a'),
        ];
        yield 'Uppercase Char' => [
            'A',
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
            'special key code',
            CodedKeyEvent::new(KeyCode::BackTab),
        ];
    }

    /**
     * @return Generator<array{0:string,1:?Event,2?:bool}>
     */
    public static function provideCsiModifierKeyCode(): Generator
    {
        yield 'special key code with types' => [
            "\x1B[1;1:3B",
            CodedKeyEvent::new(KeyCode::Down, KeyModifiers::NONE, KeyEventKind::Release),
        ];
        yield 'Shift F1' => [
            "\x1B[1;2P",
            FunctionKeyEvent::new(1, KeyModifiers::SHIFT),
        ];
        yield 'Alt F1' => [
            "\x1B[1;3P",
            FunctionKeyEvent::new(1, KeyModifiers::ALT),
        ];
        yield 'Ctl F1' => [
            "\x1B[1;5P",
            FunctionKeyEvent::new(1, KeyModifiers::CONTROL),
        ];
        yield 'Super F1' => [
            "\x1B[1;9P",
            FunctionKeyEvent::new(1, KeyModifiers::SUPER),
        ];
        yield 'Hyper F1' => [
            "\x1B[1;17P",
            FunctionKeyEvent::new(1, KeyModifiers::HYPER),
        ];
        yield 'Meta F1' => [
            "\x1B[1;33P",
            FunctionKeyEvent::new(1, KeyModifiers::META),
        ];
    }

    /**
     * @return Generator<array{0:string,1:?Event,2?:bool}>
     */
    public static function provideCsiMouse(): Generator
    {
        yield 'CSI normal mouse' => [
            "\x1B[M0\x60\x70",
            MouseEvent::new(kind: MouseEventKind::Down, button: MouseButton::Left, column:63, row: 79, modifiers: KeyModifiers::CONTROL),
        ];
        yield 'CSI RXVT normal mouse' => [
            "\x1B[32;30;40;M",
            MouseEvent::new(kind: MouseEventKind::Down, button: MouseButton::Left, column:29, row: 39, modifiers: KeyModifiers::NONE),
        ];
    }
}
