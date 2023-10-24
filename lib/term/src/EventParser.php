<?php

namespace PhpTui\Term;

use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\FocusEvent;
use PhpTui\Term\Event\FunctionKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;

class EventParser
{
    /**
     * @var string[]
     */
    private array $buffer = [];

    /**
     * @var Event[]
     */
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

    public function advance(string $line, bool $more): void
    {
        // split string into bytes
        $bytes = str_split($line);

        foreach ($bytes as $index => $byte) {
            $more = $index + 1 < strlen($line) || $more;

            $this->buffer[] = $byte;
            $event = $this->parseEvent($this->buffer, $more);
            if ($event === null) {
                continue;
            }
            $this->events[] = $event;
            $this->buffer = [];
        }
    }

    /**
     * @param string[] $buffer
     */
    private function parseEvent(array $buffer, bool $inputAvailable): ?Event
    {
        if ($buffer === []) {
            return null;
        }

        return match ($buffer[0]) {
            "\x1B" => $this->parseEsc($buffer, $inputAvailable),
            "\x7F" => CodedKeyEvent::new(KeyCode::Backspace),
            "\r" => CodedKeyEvent::new(KeyCode::Enter),
            "\t" => CodedKeyEvent::new(KeyCode::Tab),
            default => $this->parseUtf8Char($buffer),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseEsc(array $buffer, bool $inputAvailable): ?Event
    {
        if (count($buffer) === 1) {
            if ($inputAvailable) {
                // _could_ be an escape sequence
                return null;
            }

            return CodedKeyEvent::new(KeyCode::Esc);
        }

        return match ($buffer[1]) {
            '[' => $this->parseCsi($buffer),
            default => throw new ParseError(sprintf('TODO: Could not handle second byte: %s', $buffer[1])),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsi(array $buffer): ?Event
    {
        if (count($buffer) === 2) {
            return null;
        }

        return match ($buffer[2]) {
            'D' => CodedKeyEvent::new(KeyCode::Left),
            'C' => CodedKeyEvent::new(KeyCode::Right),
            'A' => CodedKeyEvent::new(KeyCode::Up),
            'B' => CodedKeyEvent::new(KeyCode::Down),
            'H' => CodedKeyEvent::new(KeyCode::Home),
            'F' => CodedKeyEvent::new(KeyCode::End),
            'I' => FocusEvent::gained(),
            'O' => FocusEvent::lost(),
            // https://sw.kovidgoyal.net/kitty/keyboard-protocol/#legacy-functional-keys
            'P' => FunctionKeyEvent::new(1),
            'Q' => FunctionKeyEvent::new(2),
            'R' => FunctionKeyEvent::new(3), // this is omitted from crossterm
            'S' => FunctionKeyEvent::new(4),
            '0','1','2','3','4','5','6','7','8','9' => $this->parseCsiMore($buffer),
            default => throw new ParseError(sprintf('TODO: Could not handle CSI byte: %s', $buffer[2])),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiMore(array $buffer): ?Event
    {
        // numbered escape code
        if (count($buffer) === 3) {
            return null;
        }

        $lastByte = $buffer[array_key_last($buffer)];
        // the final byte of a CSI sequence can be in the range 64-126
        $ord = ord($lastByte);
        if ($ord < 64 || $ord > 126) {
            return null;
        }

        return match ($lastByte) {
            '~' => $this->parseCsiSpecialKeyCode($buffer),
            default => throw new ParseError(sprintf('TODO: Could not handle last CSI byte: %s', $lastByte)),
        };
    }

    /**
     * @param string[] $buffer
     */
    private function parseCsiSpecialKeyCode(array $buffer): Event
    {
        $str = implode('', array_slice($buffer, 2, (int)array_key_last($buffer)));

        $split = array_map(
            fn (string $substr) => (int)array_reduce(
                str_split($substr),
                function (string $ac, string $char) {
                    if (false === is_numeric($char)) {
                        return $ac;
                    }
                    return $ac . $char;
                },
                ''
            ),
            explode(';', $str),
        );
        $first = $split[array_key_first($split)];

        $keycode = match ($first) {
            1,7 => KeyCode::Home,
            2 => KeyCode::Insert,
            4,8 => KeyCode::End,
            5 => KeyCode::PageUp,
            6 => KeyCode::PageDown,
            3 => KeyCode::Delete,
            default => throw new ParseError(sprintf('Could not handle special CSI byte: %s', $first)),
        };

        return CodedKeyEvent::new($keycode);
    }

    /**
     * @param string[] $buffer
     */
    private function parseUtf8Char(array $buffer): Event
    {
        if (count($buffer) !== 1) {
            throw new ParseError('Multibyte characters not supported');
        }
        $char = $buffer[0];
        return $this->charToEvent($char);
    }

    private function charToEvent(string $char): Event
    {
        $modifiers = 0;
        if (strtoupper($char) === $char) {
            $modifiers = KeyModifiers::SHIFT;
        }

        return CharKeyEvent::new($char, $modifiers);
    }

    public static function new(): self
    {
        return new self();
    }
}
